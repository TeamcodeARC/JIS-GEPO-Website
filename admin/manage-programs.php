<?php
// admin/manage-programs.php

require_once '../includes/header.php';
require_once '../includes/auth.php';
require_once '../includes/program.class.php';

class ProgramManager {
    private $db;
    private $auth;

    public function __construct($db, $auth) {
        $this->db = $db;
        $this->auth = $auth;
    }

    public function createProgram($data) {
        $stmt = $this->db->prepare("
            INSERT INTO programs (
                title, description, type, start_date, end_date, 
                partner_id, max_participants, eligibility_criteria,
                application_deadline, status, created_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['type'],
            $data['start_date'],
            $data['end_date'],
            $data['partner_id'],
            $data['max_participants'],
            json_encode($data['eligibility_criteria']),
            $data['application_deadline'],
            'active',
            $this->auth->getCurrentUser()['id']
        ]);
    }

    public function getPrograms($filters = []) {
        $query = "SELECT p.*, pa.name as partner_name 
                 FROM programs p 
                 LEFT JOIN partners pa ON p.partner_id = pa.id 
                 WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['type'])) {
            $query .= " AND p.type = ?";
            $params[] = $filters['type'];
        }

        if (!empty($filters['status'])) {
            $query .= " AND p.status = ?";
            $params[] = $filters['status'];
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateProgramStatus($programId, $status) {
        $stmt = $this->db->prepare("
            UPDATE programs 
            SET status = ?, updated_at = NOW() 
            WHERE id = ?
        ");
        return $stmt->execute([$status, $programId]);
    }

    public function getProgramApplications($programId) {
        $stmt = $this->db->prepare("
            SELECT a.*, u.first_name, u.last_name, u.email 
            FROM applications a
            JOIN users u ON a.user_id = u.id
            WHERE a.program_id = ?
        ");
        $stmt->execute([$programId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Initialize program manager
$programManager = new ProgramManager($db, $auth);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $programManager->createProgram($_POST);
                break;
            case 'update_status':
                $programManager->updateProgramStatus($_POST['program_id'], $_POST['status']);
                break;
        }
    }
}

// Get programs with filters
$filters = [
    'type' => $_GET['type'] ?? null,
    'status' => $_GET['status'] ?? 'active'
];
$programs = $programManager->getPrograms($filters);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Programs - GEPO Admin</title>
</head>
<body>
    <div class="container mx-auto px-6 py-8">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-semibold text-gray-800">Manage Programs</h1>
            <button onclick="openNewProgramModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Add New Program
            </button>
        </div>

        <!-- Filters -->
        <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
            <form class="flex gap-4" method="GET">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="exchange">Exchange Program</option>
                    <option value="research">Research Collaboration</option>
                    <option value="workshop">Workshop/Training</option>
                </select>
                <select name="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="completed">Completed</option>
                </select>
                <button type="submit" class="bg-gray-100 px-4 py-2 rounded-lg">Apply Filters</button>
            </form>
        </div>

        <!-- Programs List -->
        <div class="mt-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Partner</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($programs as $program): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($program['title']) ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500"><?= htmlspecialchars($program['partner_name']) ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500"><?= ucfirst($program['type']) ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500">
                                    <?= date('M d, Y', strtotime($program['start_date'])) ?> - 
                                    <?= date('M d, Y', strtotime($program['end_date'])) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?= $program['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                                        ($program['status'] === 'upcoming' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') ?>">
                                    <?= ucfirst($program['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="program-details.php?id=<?= $program['id'] ?>" class="text-blue-600 hover:text-blue-900">View</a>
                                <button onclick="editProgram(<?= $program['id'] ?>)" class="ml-3 text-indigo-600 hover:text-indigo-900">Edit</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- New Program Modal -->
    <div id="newProgramModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <!-- Modal content -->
    </div>

    <script src="/assets/js/program-manager.js"></script>
</body>
</html>