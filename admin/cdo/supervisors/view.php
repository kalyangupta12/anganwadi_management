<?php
// cdo/supervisors/view.php

require '../../includes/auth.php';
require '../../includes/db.php';
redirectIfNotCDO();

// Fetch the cdo_id of the logged-in CDO user
$stmt = $pdo->prepare("SELECT cdo_id FROM cdo WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cdo = $stmt->fetch();

if (!$cdo) {
    die("CDO not found.");
}

$cdo_id = $cdo['cdo_id'];

// Fetch all supervisors under this CDO
$stmt = $pdo->prepare("
    SELECT s.*, u.username 
    FROM supervisors s 
    JOIN users u ON s.user_id = u.user_id 
    WHERE s.cdo_id = ?
");
$stmt->execute([$cdo_id]);
$supervisors = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Supervisors</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">View Supervisors</h1>
        <a href="add.php" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 mb-4 inline-block">Add Supervisor</a>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aadhar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($supervisors as $supervisor): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($supervisor['name']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($supervisor['username']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($supervisor['aadhar']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($supervisor['contact_number']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($supervisor['email']); ?></td>
                            <td class="px-6 py-4">
                                <a href="edit.php?id=<?php echo $supervisor['supervisor_id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                <a href="delete.php?id=<?php echo $supervisor['supervisor_id']; ?>" class="text-red-500 hover:text-red-700 ml-2">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>