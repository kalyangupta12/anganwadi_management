<?php
// supervisor/dashboard.php

require '../includes/auth.php';
redirectIfNotSupervisor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Supervisor Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="workers/view.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <h2 class="text-xl font-semibold">Workers</h2>
                <p class="text-gray-600">Manage Workers</p>
            </a>
            <a href="centres/view.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <h2 class="text-xl font-semibold">Centres</h2>
                <p class="text-gray-600">Manage Centres</p>
            </a>
        </div>
    </div>
</body>
</html>