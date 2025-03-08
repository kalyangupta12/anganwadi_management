<?php
// supervisor/workers/delete.php


require '../../includes/auth.php';
require '../../includes/db.php';
require '../../includes/functions.php';
redirectIfNotSupervisor();

if (!isset($_GET['id'])) {
    header('Location: view.php');
    exit();
}

$worker_id = $_GET['id'];

// Delete worker
$stmt = $pdo->prepare("DELETE FROM workers WHERE worker_id = ?");
$stmt->execute([$worker_id]);

header('Location: view.php');
exit();
?>