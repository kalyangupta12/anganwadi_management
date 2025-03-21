<?php
// children/mark_attendance.php

require '../../includes/auth.php';
require '../../includes/db.php';
require '../../includes/functions.php';
redirectIfNotWorker();

// Set the default timezone to India
date_default_timezone_set('Asia/Kolkata');

header('Content-Type: application/json'); // Ensure the response is JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $child_id = $_POST['child_id'];
    $worker_id = $_POST['worker_id'];
    $centre_id = $_POST['centre_id'];
    $datetime = date('Y-m-d H:i:s'); // Current timestamp in India timezone
    $status = 'present'; // Default status

    // Validate input
    if (empty($child_id) || empty($worker_id) || empty($centre_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit();
    }

    // Check if worker_id exists in the workers table
    $stmt = $pdo->prepare("SELECT worker_id FROM workers WHERE worker_id = ?");
    $stmt->execute([$worker_id]);
    if (!$stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid worker ID']);
        exit();
    }

    // Check if attendance is already marked for the same child on the same day
    $currentDate = date('Y-m-d'); // Get the current date (without time)
    $stmt = $pdo->prepare("
        SELECT * FROM attendance 
        WHERE child_id = ? 
        AND DATE(datetime) = ?
    ");
    $stmt->execute([$child_id, $currentDate]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Attendance already marked for this child today']);
        exit();
    }

    // Insert attendance record
    $stmt = $pdo->prepare("
        INSERT INTO attendance (centre_id, worker_id, child_id, datetime, status, marked_by)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    if ($stmt->execute([$centre_id, $worker_id, $child_id, $datetime, $status, $worker_id])) {
        echo json_encode(['status' => 'success', 'message' => 'Attendance marked successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to mark attendance']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}