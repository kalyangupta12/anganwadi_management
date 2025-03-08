<?php
// includes/auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isCDO() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'CDO';
}

function isSupervisor() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Supervisor';
}

function isWorker() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Worker';
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit();
    }
}

function redirectIfNotCDO() {
    if (!isCDO()) {
        header('Location: ../login.php');
        exit();
    }
}

function redirectIfNotSupervisor() {
    if (!isSupervisor()) {
        header('Location: ../login.php');
        exit();
    }
}

function redirectIfNotWorker() {
    if (!isWorker()) {
        header('Location: ../login.php');
        exit();
    }
}
?>