<?php
// worker/dashboard.php


require '../includes/auth.php';
require '../includes/db.php';
require '../includes/functions.php';
redirectIfNotWorker();

$user_id = $_SESSION['user_id'];

// Fetch worker's assigned centre
$stmt = $pdo->prepare("SELECT centre_id FROM workers WHERE user_id = ?");
$stmt->execute([$user_id]);
$worker = $stmt->fetch();

if (!$worker) {
    header('Location: ../login.php');
    exit();
}

$centre_id = $worker['centre_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Worker Dashboard</h1>
        <!-- worker/dashboard.php -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="children/register.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <h2 class="text-xl font-semibold">Register Child</h2>
                <p class="text-gray-600">Register a new child</p>
            </a>
            <a href="children/view.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <h2 class="text-xl font-semibold">View Children</h2>
                <p class="text-gray-600">View registered children</p>
            </a>
            <a href="children/attendance.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <h2 class="text-xl font-semibold">Mark Attendance</h2>
                <p class="text-gray-600">Mark attendance using facial recognition</p>
            </a>
            <a href="ration/view.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <h2 class="text-xl font-semibold">Ration Distribution</h2>
                <p class="text-gray-600">View Ration Distribution</p>
            </a>
        </div>
    </div>
</body>

</html>