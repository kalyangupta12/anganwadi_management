<?php
// supervisor/centres/delete.php

require '../../includes/auth.php';
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
    die("You do not have permission to delete this centre.");
}

// Delete the centre
$stmt = $pdo->prepare("DELETE FROM anganwadicentres WHERE centre_id = ?");
$stmt->execute([$centre_id]);

// Redirect to view page
header('Location: view.php');
exit();
?>