<?php
// events/calendar.php

require_once '../includes/header.php';
require_once '../includes/auth.php';

class EventsCalendar {
    private $db;
    private $auth;

    public function __construct($db, $auth) {
        $this->db = $db;
        $this->auth = $auth;
    }

    public function getEvents($month, $year) {
        $stmt = $this->db->prepare("
            SELECT e.*, 
                   p.title as program_title,
                   COUNT(r.id) as registrations_count
            FROM events e
            LEFT JOIN programs p ON e.program_id = p.id
            LEFT JOIN event_registrations r ON e.id = r.event_id
            WHERE MONTH(e.start_date) = ? AND YEAR(e.start_date) = ?
            GROUP BY e.id
            ORDER BY e.start_date ASC
        ");
        
        $stmt->execute([$month, $year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEvent($data) {
        $stmt = $this->db->prepare("
            INSERT INTO events (
                title, description, type, start_date, end_date,
                location, virtual_link, max_participants, program_id,
                registration_deadline, created_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['type'],
            $data['start_date'],
            $data['end_date'],
            $data['location'],
            $data['virtual_link'],
            $data['max_participants'],
            $data['program_id'],
            $data['registration_deadline'],
            $this->auth->getCurrentUser()['id']
        ]);
    }

    public function registerForEvent($eventId, $userId) {
        // Check if registration is still open
        $event = $this->getEvent($eventId);
        if (strtotime($event['registration_deadline']) < time()) {
            throw new Exception('Registration deadline has passed');
        }

        // Check if there's still space
        if ($event['registrations_count'] >= $event['max_participants']) {
            throw new Exception('Event is fully booked');
        }

        $stmt = $this->db->prepare("
            INSERT INTO event_registrations (event_id, user_id, status)
            VALUES (?, ?, 'registered')
        ");
        return $stmt->execute([$eventId, $userId]);
    }
}

// Initialize calendar
$calendar = new EventsCalendar($db, $auth);

// Get current month and year
$month = $_GET['month'] ?? date('n');
$year = $_GET['year'] ?? date('Y');

// Get events for the current month
$events = $calendar->getEvents($month, $year);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Events Calendar - GEPO</title>
    <link rel="stylesheet" href="/assets/css/calendar.css">
</head>
<body>
    <div class="container mx-auto px-6 py-8">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-semibold text-gray-800">Events Calendar</h1>
            <?php if ($auth->isAdmin()): ?>
            <button onclick="openNewEventModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Add New Event
            </button>
            <?php endif; ?>
        </div>

        <!-- Calendar Navigation -->
        <div class="mt-8 flex justify-between items-center">
            <div class="flex gap-4">
                <a href="?month=<?= $month-1 ?>&year=<?= $year ?>" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-chevron-left"></i> Previous Month
                </a>
                <h2 class="text-xl font-medium">
                    <?= date('F Y', mktime(0, 0, 0, $month, 1, $year)) ?>
                </h2>
                <a href="?month=<?= $month+1 ?>&year=<?= $year ?>" class="text-gray-600 hover:text-gray-900">
                    Next Month <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <div class="flex gap-2">
                <button class="px-3 py-1 rounded-lg bg-gray-100" onclick="changeView('month')">Month</button>
                <button class="px-3 py-1 rounded-lg bg-gray-100" onclick="changeView('week')">Week</button>
                <button class="px-3 py-1 rounded-lg bg-gray-100" onclick="changeView('day')">Day</button>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                <?php
                $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                foreach ($days as $day): ?>
                    <div class="bg-gray-50 py-2 px-3 text-center text-sm font-medium text-gray-500">
                        <?= $day ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="grid grid-cols-7 gap-px bg-gray-200">
                <?php
                $firstDay = mktime(0, 0, 0, $month, 1, $year);
                $daysInMonth = date('t', $firstDay);
                $startingDay = date('w', $firstDay);
                $day = 1;

                // Blank days before start of month
                for ($i = 0; $i < $startingDay; $i++): ?>
                    <div class="bg-white min-h-[120px]"></div>
                <?php endfor;

                // Days of the month
                while ($day <= $daysInMonth):
                    $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $dayEvents = array_filter($events, function($event) use ($date) {
                        return date('Y-m-d', strtotime($event['start_date'])) === $date;
                    });
                ?>
                    <div class="bg-white min-