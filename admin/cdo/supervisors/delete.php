<?php
// cdo/supervisors/delete.php

require '../../includes/auth.php';
require '../../includes/db.php';
redirectIfNotCDO();

if (!isset($_GET['id'])) {
    header('Location: view.php');
    exit();
}

$supervisor_id = $_GET['id'];

// Fetch supervisor details
$stmt = $pdo->prepare("
    SELECT s.* 
    FROM supervisors s 
    WHERE s.supervisor_id = ?
");
$stmt->execute([$supervisor_id]);
$supervisor = $stmt->fetch();

if (!$supervisor) {
    header('Location: view.php');
    exit();
}

// Fetch the cdo_id of the logged-in CDO user
$stmt = $pdo->prepare("SELECT cdo_id FROM cdo WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cdo = $stmt->fetch();

if (!$cdo) {
    die("CDO not found.");
}

$cdo_id = $cdo['cdo_id'];

// Ensure the supervisor belongs to the logged-in CDO user
if ($supervisor['cdo_id'] !== $cdo_id) {
    die("You do not have permission to delete this supervisor.");
}

// Begin transaction
$pdo->beginTransaction();

try {
    // Delete from supervisors table
    $stmt = $pdo->prepare("DELETE FROM supervisors WHERE supervisor_id = ?");
    $stmt->execute([$supervisor_id]);

    // Delete from users table
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$supervisor['user_id']]);

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
?>