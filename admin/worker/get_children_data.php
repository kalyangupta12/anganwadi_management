<?php
// get_children_data.php

require '../includes/auth.php';
require '../includes/db.php';
require '../includes/functions.php';
redirectIfNotWorker();

$user_id = $_SESSION['user_id'];

// Fetch worker's assigned centre
$stmt = $pdo->prepare("SELECT * FROM workers WHERE user_id = ?");
$stmt->execute([$user_id]);
$worker = $stmt->fetch();

if (!$worker) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Worker not found']);
    exit();
}

$centre_id = $worker['centre_id'];

// Fetch children with their image paths
$stmt = $pdo->prepare("SELECT child_id, name, image_path FROM children WHERE centre_id = ? AND image_path IS NOT NULL");
$stmt->execute([$centre_id]);
$children = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return as JSON
header('Content-Type: application/json');
echo json_encode($children);
exit();
?>