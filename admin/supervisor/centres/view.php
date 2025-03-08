<?php
// supervisor/centres/view.php

require '../../includes/auth.php';
require '../../includes/db.php';
redirectIfNotSupervisor();

// Fetch the supervisor_id of the logged-in supervisor
$stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$supervisor = $stmt->fetch();

if (!$supervisor) {
    die("Supervisor not found.");
}

$supervisor_id = $supervisor['supervisor_id'];

// Fetch all centres managed by this supervisor
$stmt = $pdo->prepare("SELECT * FROM anganwadicentres WHERE supervisor_id = ?");
$stmt->execute([$supervisor_id]);
$centres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Anganwadi Centres</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">View Anganwadi Centres</h1>
        <a href="add.php" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 mb-4 inline-block">Add Centre</a>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Centre Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($centres as $centre): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($centre['centre_name']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($centre['location']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($centre['contact_number']); ?></td>
                            <td class="px-6 py-4">
                                <a href="edit.php?id=<?php echo $centre['centre_id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                <a href="delete.php?id=<?php echo $centre['centre_id']; ?>" class="text-red-500 hover:text-red-700 ml-2">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>