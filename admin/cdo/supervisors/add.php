<?php
// cdo/supervisors/add.php

require '../../includes/auth.php';
require '../../includes/functions.php';
require '../../includes/db.php';
redirectIfNotCDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name = sanitizeInput($_POST['name']);
    $username = sanitizeInput($_POST['username']);
    $password = hashPassword($_POST['password']);
    $aadhar = sanitizeInput($_POST['aadhar']);
    $address = sanitizeInput($_POST['address']);
    $town_village = sanitizeInput($_POST['town_village']);
    $contact_number = sanitizeInput($_POST['contact_number']);
    $email = sanitizeInput($_POST['email']);

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // Insert into users table
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'Supervisor')");
        $stmt->execute([$username, $password]);
        $user_id = $pdo->lastInsertId();

        // Fetch the cdo_id associated with the logged-in CDO user
        $stmt = $pdo->prepare("SELECT cdo_id FROM cdo WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $cdo = $stmt->fetch();

        if (!$cdo) {
            throw new Exception("CDO not found.");
        }

        $cdo_id = $cdo['cdo_id'];

        // Insert into supervisors table
        $stmt = $pdo->prepare("INSERT INTO supervisors (user_id, cdo_id, name, aadhar, address, town_village, contact_number, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $cdo_id, $name, $aadhar, $address, $town_village, $contact_number, $email]);

        // Commit transaction
        $pdo->commit();

        // Redirect to view page
        header('Location: view.php');
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Supervisor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Add Supervisor</h1>
        <form method="POST" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="aadhar" class="block text-sm font-medium text-gray-700">Aadhar Number</label>
                <input type="text" name="aadhar" id="aadhar" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea name="address" id="address" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required></textarea>
            </div>
            <div class="mb-4">
                <label for="town_village" class="block text-sm font-medium text-gray-700">Town/Village</label>
                <input type="text" name="town_village" id="town_village" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Add Supervisor</button>
        </form>
    </div>
</body>
</html>