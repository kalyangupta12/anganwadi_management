<?php
// worker/children/view.php

require '../../includes/auth.php';
require '../../includes/db.php';
require '../../includes/functions.php';
redirectIfNotWorker();

$user_id = $_SESSION['user_id'];

// Fetch worker's assigned centre
$stmt = $pdo->prepare("SELECT centre_id FROM workers WHERE user_id = ?");
$stmt->execute([$user_id]);
$worker = $stmt->fetch();

if (!$worker) {
    header('Location: ../../login.php');
    exit();
}

$centre_id = $worker['centre_id'];

// Fetch children under the worker's centre
$stmt = $pdo->prepare("SELECT * FROM children WHERE centre_id = ?");
$stmt->execute([$centre_id]);
$children = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Children</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">View Children</h1>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">DOB</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aadhar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parent Name</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($children as $child): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo $child['name']; ?></td>
                            <td class="px-6 py-4"><?php echo $child['dob']; ?></td>
                            <td class="px-6 py-4"><?php echo $child['gender']; ?></td>
                            <td class="px-6 py-4"><?php echo $child['aadhar_card']; ?></td>
                            <td class="px-6 py-4"><?php echo $child['parent_name']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>