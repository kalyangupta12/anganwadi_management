<?php
// worker/ration/view.php


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

// Fetch ration distribution under the worker's centre
$stmt = $pdo->prepare("
    SELECT r.*, ch.name AS child_name 
    FROM rationdistribution r 
    JOIN children ch ON r.child_id = ch.child_id 
    WHERE r.centre_id = ?
");
$stmt->execute([$centre_id]);
$rations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Ration Distribution</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">View Ration Distribution</h1>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Child Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ration Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($rations as $ration): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo $ration['child_name']; ?></td>
                            <td class="px-6 py-4"><?php echo $ration['ration_type']; ?></td>
                            <td class="px-6 py-4"><?php echo $ration['quantity']; ?></td>
                            <td class="px-6 py-4"><?php echo $ration['datetime']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>