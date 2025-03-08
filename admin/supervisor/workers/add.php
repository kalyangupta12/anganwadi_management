<?php
// supervisor/workers/add.php

require '../../includes/auth.php';
require '../../includes/db.php';
require '../../includes/functions.php';
redirectIfNotSupervisor();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $username = sanitizeInput($_POST['username']);
    $password = hashPassword($_POST['password']);
    $gender = sanitizeInput($_POST['gender']);
    $aadhar = sanitizeInput($_POST['aadhar']);
    $emergency_contact = sanitizeInput($_POST['emergency_contact']);
    $contact_number = sanitizeInput($_POST['contact_number']);
    $email = sanitizeInput($_POST['email']);
    $centre_id = sanitizeInput($_POST['centre_id']);

    try {
        $pdo->beginTransaction();

        // Insert into users table
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'Worker')");
        $stmt->execute([$username, $password]);
        $user_id = $pdo->lastInsertId();

        if (!$user_id) {
            throw new Exception("Failed to insert user.");
        }

        // Insert into workers table
        $stmt = $pdo->prepare("INSERT INTO workers (user_id, centre_id, name, gender, aadhar, emergency_contact, contact_number, email) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $centre_id, $name, $gender, $aadhar, $emergency_contact, $contact_number, $email]);

        $pdo->commit();
        header('Location: view.php');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}

// Fetch Anganwadi Centres for dropdown
$stmt = $pdo->prepare("SELECT * FROM anganwadicentres WHERE supervisor_id = (SELECT supervisor_id FROM supervisors WHERE user_id = ?)");
$stmt->execute([$_SESSION['user_id']]);
$centres = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Worker</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Add Worker</h1>
        <br>
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
                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                <select name="gender" id="gender" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="aadhar" class="block text-sm font-medium text-gray-700">Aadhar Number</label>
                <input type="text" name="aadhar" id="aadhar" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="emergency_contact" class="block text-sm font-medium text-gray-700">Emergency Contact</label>
                <input type="text" name="emergency_contact" id="emergency_contact" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="centre_id" class="block text-sm font-medium text-gray-700">Anganwadi Centre</label>
                <select name="centre_id" id="centre_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                    <?php foreach ($centres as $centre): ?>
                        <option value="<?php echo $centre['centre_id']; ?>"><?php echo $centre['centre_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Add Worker</button>
        </form>
    </div>
</body>
</html>