<?php
// supervisor/ration/view.php

require '../../includes/auth.php';
redirectIfNotSupervisor();

// Fetch ration distribution under the supervisor's centres
$stmt = $pdo->prepare("
    SELECT r.*, c.centre_name, ch.name AS child_name 
    FROM rationdistribution r 
    JOIN anganwadicentres c ON r.centre_id = c.centre_id 
    JOIN children ch ON r.child_id = ch.child_id 
    WHERE c.supervisor_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Centre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ration Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Distributed By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($rations as $ration): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo $ration['child_name']; ?></td>
                            <td class="px-6 py-4"><?php echo $ration['centre_name']; ?></td>
                            <td class="px-6 py-4"><?php echo $ration['ration_type']; ?></td>
                            <td class="px-6 py-4"><?php echo $ration['quantity']; ?></td>
                            <td class="px-6 py-4"><?php echo $ration['distributed_by']; ?></td>
                            <td class="px-6 py-4"><?php echo $ration['datetime']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>