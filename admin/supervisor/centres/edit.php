<?php
// supervisor/centres/edit.php

require '../../includes/auth.php';
require '../../includes/functions.php';
require '../../includes/db.php';
redirectIfNotSupervisor();

if (!isset($_GET['id'])) {
    header('Location: view.php');
    exit();
}

$centre_id = $_GET['id'];

// Fetch centre details
$stmt = $pdo->prepare("SELECT * FROM anganwadicentres WHERE centre_id = ?");
$stmt->execute([$centre_id]);
$centre = $stmt->fetch();

if (!$centre) {
    header('Location: view.php');
    exit();
}

// Fetch the supervisor_id of the logged-in supervisor
$stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$supervisor = $stmt->fetch();

if (!$supervisor) {
    die("Supervisor not found.");
}

$supervisor_id = $supervisor['supervisor_id'];

// Ensure the centre belongs to the logged-in supervisor
if ($centre['supervisor_id'] !== $supervisor_id) {
    die("You do not have permission to edit this centre.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $centre_name = sanitizeInput($_POST['centre_name']);
    $location = sanitizeInput($_POST['location']);
    $contact_number = sanitizeInput($_POST['contact_number']);

    // Update the centre
    $stmt = $pdo->prepare("UPDATE anganwadicentres SET centre_name = ?, location = ?, contact_number = ? WHERE centre_id = ?");
    $stmt->execute([$centre_name, $location, $contact_number, $centre_id]);

    // Redirect to view page
    header('Location: view.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Anganwadi Centre</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Edit Anganwadi Centre</h1>
        <form method="POST" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="centre_name" class="block text-sm font-medium text-gray-700">Centre Name</label>
                <input type="text" name="centre_name" id="centre_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo htmlspecialchars($centre['centre_name']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                <input type="text" name="location" id="location" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo htmlspecialchars($centre['location']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo htmlspecialchars($centre['contact_number']); ?>">
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Update Centre</button>
        </form>
    </div>
</body>
</html>