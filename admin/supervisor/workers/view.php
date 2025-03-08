<?php
// supervisor/workers/view.php

require '../../includes/auth.php';
require '../../includes/db.php';
require '../../includes/functions.php';
redirectIfNotSupervisor();

// Fetch workers under the supervisor's centres
$stmt = $pdo->prepare("
    SELECT w.*, c.centre_name 
    FROM workers w 
    JOIN anganwadicentres c ON w.centre_id = c.centre_id 
    WHERE c.supervisor_id = (SELECT supervisor_id FROM supervisors WHERE user_id = ?)
");
$stmt->execute([$_SESSION['user_id']]);
$workers = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Workers</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">View Workers</h1>
        <a href="add.php" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 mb-4 inline-block">Add Worker</a>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aadhar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Centre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($workers as $worker): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo $worker['name']; ?></td>
                            <td class="px-6 py-4"><?php echo $worker['gender']; ?></td>
                            <td class="px-6 py-4"><?php echo $worker['aadhar']; ?></td>
                            <td class="px-6 py-4"><?php echo $worker['contact_number']; ?></td>
                            <td class="px-6 py-4"><?php echo $worker['centre_name']; ?></td>
                            <td class="px-6 py-4">
                                <a href="edit.php?id=<?php echo $worker['worker_id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                <a href="delete.php?id=<?php echo $worker['worker_id']; ?>" class="text-red-500 hover:text-red-700 ml-2">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>