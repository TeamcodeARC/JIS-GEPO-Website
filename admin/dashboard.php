<?php
// admin/dashboard.php
require_once '../includes/header.php';
require_once '../includes/auth.php';

// Check if user is admin
if (!$auth->isAdmin()) {
    header('Location: /login.php');
    exit;
}

$user = $auth->getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEPO Admin Dashboard</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-xl">
            <div class="p-4">
                <img src="/assets/images/logo.png" alt="GEPO Logo" class="h-8">
            </div>
            <nav class="mt-8">
                <a href="dashboard.php" class="flex items-center px-6 py-3 text-gray-700 bg-gray-100">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="partners.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-handshake mr-3"></i>
                    Partners
                </a>
                <a href="programs.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-graduation-cap mr-3"></i>
                    Programs
                </a>
                <a href="events.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-calendar mr-3"></i>
                    Events
                </a>
                <a href="users.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-users mr-3"></i>
                    Users
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                <h3 class="text-gray-700 text-3xl font-medium">Dashboard</h3>

                <!-- Stats Grid -->
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php
                    $stats = [
                        [
                            'title' => 'Total Partners',
                            'value' => $dashboard->getPartnersCount(),
                            'icon' => 'handshake',
                            'color' => 'blue'
                        ],
                        [
                            'title' => 'Active Programs',
                            'value' => $dashboard->getActivePrograms(),
                            'icon' => 'graduation-cap',
                            'color' => 'green'
                        ],
                        [
                            'title' => 'Upcoming Events',
                            'value' => $dashboard->getUpcomingEvents(),
                            'icon' => 'calendar',
                            'color' => 'yellow'
                        ],
                        [
                            'title' => 'Total Users',
                            'value' => $dashboard->getUsersCount(),
                            'icon' => 'users',
                            'color' => 'purple'
                        ]
                    ];

                    foreach ($stats as $stat): ?>
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-<?= $stat['color'] ?>-100 text-<?= $stat['color'] ?>-500">
                                    <i class="fas fa-<?= $stat['icon'] ?> fa-2x"></i>
                                </div>
                                <div class="mx-5">
                                    <h4 class="text-2xl font-semibold text-gray-700"><?= $stat['value'] ?></h4>
                                    <div class="text-gray-500"><?= $stat['title'] ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Recent Activity -->
                <div class="mt-8">
                    <div class="flex items-center justify-between">
                        <h4 class="text-gray-600">Recent Activity</h4>
                        <a href="activity.php" class="text-blue-600 hover:text-blue-800">View All</a>
                    </div>
                    <div class="mt-4">
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Action
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            User
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($dashboard->getRecentActivity() as $activity): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?= htmlspecialchars($activity['action']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?= htmlspecialchars($activity['user']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500"><?= $activity['date'] ?></div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="/assets/js/admin.js"></script>
</body>
</html>