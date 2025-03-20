<?php
require '../includes/db.php'; 
require '../includes/auth.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$supervisor_id = 1; // Replace with dynamic supervisor_id

$stmt = $pdo->prepare("SELECT * FROM anganwadicentres WHERE supervisor_id = ? AND centre_name LIKE ?");
$stmt->execute([$supervisor_id, "%$search%"]);
$centres = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($centres);
?>
