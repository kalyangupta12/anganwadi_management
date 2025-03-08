<?php
// supervisor/workers/edit.php


require '../../includes/auth.php';
require '../../includes/db.php';
require '../../includes/functions.php';
redirectIfNotSupervisor();

if (!isset($_GET['id'])) {
    header('Location: view.php');
    exit();
}

$worker_id = $_GET['id'];

// Fetch worker details
$stmt = $pdo->prepare("SELECT * FROM workers WHERE worker_id = ?");
$stmt->execute([$worker_id]);
$worker = $stmt->fetch();

if (!$worker) {
    header('Location: view.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $gender = sanitizeInput($_POST['gender']);
    $aadhar = sanitizeInput($_POST['aadhar']);
    $emergency_contact = sanitizeInput($_POST['emergency_contact']);
    $contact_number = sanitizeInput($_POST['contact_number']);
    $email = sanitizeInput($_POST['email']);
    $centre_id = sanitizeInput($_POST['centre_id']);

    $stmt = $pdo->prepare("UPDATE workers SET name = ?, gender = ?, aadhar = ?, emergency_contact = ?, contact_number = ?, email = ?, centre_id = ? WHERE worker_id = ?");
    $stmt->execute([$name, $gender, $aadhar, $emergency_contact, $contact_number, $email, $centre_id, $worker_id]);

    header('Location: view.php');
    exit();
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
    <title>Edit Worker</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Edit Worker</h1>
        <form method="POST" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo $worker['name']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                <select name="gender" id="gender" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                    <option value="Male" <?php echo $worker['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo $worker['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo $worker['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="aadhar" class="block text-sm font-medium text-gray-700">Aadhar Number</label>
                <input type="text" name="aadhar" id="aadhar" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo $worker['aadhar']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="emergency_contact" class="block text-sm font-medium text-gray-700">Emergency Contact</label>
                <input type="text" name="emergency_contact" id="emergency_contact" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo $worker['emergency_contact']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo $worker['contact_number']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo $worker['email']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="centre_id" class="block text-sm font-medium text-gray-700">Anganwadi Centre</label>
                <select name="centre_id" id="centre_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                    <?php foreach ($centres as $centre): ?>
                        <option value="<?php echo $centre['centre_id']; ?>" <?php echo $centre['centre_id'] === $worker['centre_id'] ? 'selected' : ''; ?>><?php echo $centre['centre_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Update Worker</button>
        </form>
    </div>
</body>
</html>