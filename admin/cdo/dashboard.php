<?php
// cdo/dashboard.php

require '../includes/auth.php';
require '../includes/db.php';
require '../includes/functions.php';
redirectIfNotCDO();

// Determine the action from the query parameter
$action = isset($_GET['action']) ? $_GET['action'] : 'view';

// Define valid actions
$validActions = ['view', 'add', 'edit', 'delete', 'view_notifications', 'add_notification', 'edit_notification', 'delete_notification', 'view_worker', 'view_reports'];

// Default to 'view' if the action is invalid
if (!in_array($action, $validActions)) {
    $action = 'view';
}

// Handle actions
switch ($action) {
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize inputs
            $name = sanitizeInput($_POST['name']);
            $username = sanitizeInput($_POST['username']);
            $password = hashPassword($_POST['password']);
            $id_type = sanitizeInput($_POST['id_type']);
            $aadhar = sanitizeInput($_POST['aadhar']);
            $address = sanitizeInput($_POST['address']);
            $town_village = sanitizeInput($_POST['town_village']);
            $contact_number = sanitizeInput($_POST['contact_number']);
            $email = sanitizeInput($_POST['email']);
            $qualification_type = sanitizeInput($_POST['qualification_type']);
    
            // Handle file uploads
            $id_path = uploadFile($_FILES['id_path'], '../uploads/supervisor_docs/id_docs');
            $qualification_path = uploadFile($_FILES['qualification_path'], '../uploads/supervisor_docs/qualification_docs');
    
            // Begin transaction
            $pdo->beginTransaction();
    
            try {
                // Insert into users table
                $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'Supervisor')");
                $stmt->execute([$username, $password]);
                $user_id = $pdo->lastInsertId();
    
                // Fetch the cdo_id associated with the logged-in CDO user
                $stmt = $pdo->prepare("SELECT cdo_id FROM cdo WHERE user_id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $cdo = $stmt->fetch();
    
                if (!$cdo) {
                    throw new Exception("CDO not found.");
                }
    
                $cdo_id = $cdo['cdo_id'];
    
                // Insert into supervisors table
                $stmt = $pdo->prepare("
                    INSERT INTO supervisors 
                    (user_id, cdo_id, name, id_type, id_path, aadhar, address, town_village, contact_number, email, qualification_type, qualification_path) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $user_id, $cdo_id, $name, $id_type, $id_path, $aadhar, $address, $town_village, $contact_number, $email, $qualification_type, $qualification_path
                ]);
    
                // Commit transaction
                $pdo->commit();
    
                // Redirect to view page
                header('Location: dashboard.php?action=view');
                exit();
            } catch (Exception $e) {
                // Rollback transaction on error
                $pdo->rollBack();
                die("Error: " . $e->getMessage());
            }
        }
        break;

        case 'edit':
            if (!isset($_GET['id'])) {
                header('Location: dashboard.php?action=view');
                exit();
            }
        
            $supervisor_id = $_GET['id'];
        
            // Fetch supervisor details
            $stmt = $pdo->prepare("
                SELECT s.*, u.username 
                FROM supervisors s 
                JOIN users u ON s.user_id = u.user_id 
                WHERE s.supervisor_id = ?
            ");
            $stmt->execute([$supervisor_id]);
            $supervisor = $stmt->fetch();
        
            if (!$supervisor) {
                header('Location: dashboard.php?action=view');
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
                die("You do not have permission to edit this supervisor.");
            }
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Sanitize inputs
                $name = sanitizeInput($_POST['name']);
                $username = sanitizeInput($_POST['username']);
                $id_type = sanitizeInput($_POST['id_type']);
                $aadhar = sanitizeInput($_POST['aadhar']);
                $address = sanitizeInput($_POST['address']);
                $town_village = sanitizeInput($_POST['town_village']);
                $contact_number = sanitizeInput($_POST['contact_number']);
                $email = sanitizeInput($_POST['email']);
                $qualification_type = sanitizeInput($_POST['qualification_type']);
        
                // Handle file uploads
                $id_path = $supervisor['id_path'];
                $qualification_path = $supervisor['qualification_path'];
        
                if (!empty($_FILES['id_path']['name'])) {
                    // Delete old ID document
                    if (file_exists($id_path)) {
                        unlink($id_path);
                    }
                    $id_path = uploadFile($_FILES['id_path'], '../uploads/supervisor_docs/id_docs');
                }
        
                if (!empty($_FILES['qualification_path']['name'])) {
                    // Delete old qualification document
                    if (file_exists($qualification_path)) {
                        unlink($qualification_path);
                    }
                    $qualification_path = uploadFile($_FILES['qualification_path'], '../uploads/supervisor_docs/qualification_docs');
                }
        
                // Begin transaction
                $pdo->beginTransaction();
        
                try {
                    // Update users table
                    $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE user_id = ?");
                    $stmt->execute([$username, $supervisor['user_id']]);
        
                    // Update supervisors table
                    $stmt = $pdo->prepare("
                        UPDATE supervisors 
                        SET name = ?, id_type = ?, id_path = ?, aadhar = ?, address = ?, town_village = ?, contact_number = ?, email = ?, qualification_type = ?, qualification_path = ? 
                        WHERE supervisor_id = ?
                    ");
                    $stmt->execute([
                        $name, $id_type, $id_path, $aadhar, $address, $town_village, $contact_number, $email, $qualification_type, $qualification_path, $supervisor_id
                    ]);
        
                    // Commit transaction
                    $pdo->commit();
        
                    // Redirect to view page
                    header('Location: dashboard.php?action=view');
                    exit();
                } catch (Exception $e) {
                    // Rollback transaction on error
                    $pdo->rollBack();
                    die("Error: " . $e->getMessage());
                }
            }
            break;

            case 'delete':
                if (!isset($_GET['id'])) {
                    header('Location: dashboard.php?action=view');
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
                    header('Location: dashboard.php?action=view');
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
            
                // Check if the supervisor is linked to any Anganwadi Centres
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM anganwadicentres WHERE supervisor_id = ?");
                $stmt->execute([$supervisor_id]);
                $linkedCentresCount = $stmt->fetchColumn();
            
                if ($linkedCentresCount > 0) {
                    // If linked to Anganwadi Centres, redirect to a reassignment page
                    header('Location: reassign_supervisor.php?id=' . $supervisor_id);
                    exit();
                }
            
                // If not linked to any Anganwadi Centres, proceed with deletion
                // Begin transaction
                $pdo->beginTransaction();
            
                try {
                    // Delete documents (id_path and qualification_path) if they exist
                    if (!empty($supervisor['id_path']) && file_exists($supervisor['id_path'])) {
                        unlink($supervisor['id_path']);
                    }
                    if (!empty($supervisor['qualification_path']) && file_exists($supervisor['qualification_path'])) {
                        unlink($supervisor['qualification_path']);
                    }
            
                    // Delete from supervisors table
                    $stmt = $pdo->prepare("DELETE FROM supervisors WHERE supervisor_id = ?");
                    $stmt->execute([$supervisor_id]);
            
                    // Delete from users table
                    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
                    $stmt->execute([$supervisor['user_id']]);
            
                    // Commit transaction
                    $pdo->commit();
            
                    // Redirect to view page
                    header('Location: dashboard.php?action=view');
                    exit();
                } catch (Exception $e) {
                    // Rollback transaction on error
                    $pdo->rollBack();
                    die("Error: " . $e->getMessage());
                }
                break;
                case 'add_notification':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Sanitize inputs
                        $title =  sanitizeInput($_POST['title']);
                        $recipient_type = "Supervisor";
                        $message = sanitizeInput($_POST['message']);                
                        // Fetch the cdo_id of the logged-in CDO user
                        $stmt = $pdo->prepare("SELECT cdo_id FROM cdo WHERE user_id = ?");
                        $stmt->execute([$_SESSION['user_id']]);
                        $cdo = $stmt->fetch();
                
                        if (!$cdo) {
                            die("CDO not found.");
                        }
                
                        $cdo_id = $cdo['cdo_id'];
                
                        // Insert into notifications table
                        $stmt = $pdo->prepare("
                            INSERT INTO notifications 
                            (cdo_id, title, recipient_type, message) 
                            VALUES (?, ?, ?, ?)
                        ");
                        $stmt->execute([$cdo_id, $title, $recipient_type, $message]);
                
                        // Redirect to view notifications page
                        header('Location: dashboard.php?action=view_notifications');
                        exit();
                    }
                    break;
                    
                    case 'view_notifications':
                        // Fetch the cdo_id of the logged-in CDO user
                        $stmt = $pdo->prepare("SELECT cdo_id FROM cdo WHERE user_id = ?");
                        $stmt->execute([$_SESSION['user_id']]);
                        $cdo = $stmt->fetch();
                    
                        if (!$cdo) {
                            die("CDO not found.");
                        }
                    
                        $cdo_id = $cdo['cdo_id'];
                    
                        // Fetch all notifications for this CDO
                        $stmt = $pdo->prepare("
                            SELECT n.*, s.name AS supervisor_name, w.name AS worker_name
                            FROM notifications n
                            LEFT JOIN supervisors s ON n.recipient_type = 'Supervisor'
                            LEFT JOIN workers w ON n.recipient_type = 'Worker'
                            WHERE n.cdo_id = ?
                        ");
                        $stmt->execute([$cdo_id]);
                        $notifications = $stmt->fetchAll();
                        break;
                    
                        case 'edit_notification':
                            if (!isset($_GET['id'])) {
                                header('Location: dashboard.php?action=view_notifications');
                                exit();
                            }
                        
                            $notification_id = $_GET['id'];
                        
                            // Fetch notification details
                            $stmt = $pdo->prepare("
                                SELECT n.* 
                                FROM notifications n 
                                WHERE n.notification_id = ?
                            ");
                            $stmt->execute([$notification_id]);
                            $notification = $stmt->fetch();
                        
                            if (!$notification) {
                                header('Location: dashboard.php?action=view_notifications');
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
                        
                            // Ensure the notification belongs to the logged-in CDO user
                            if ($notification['cdo_id'] !== $cdo_id) {
                                die("You do not have permission to edit this notification.");
                            }
                        
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                // Sanitize inputs
                                $title = sanitizeInput($_POST['title']);
                                $recipient_type = sanitizeInput($_POST['target']);
                                $message = sanitizeInput($_POST['message']);                        
                                // Update notifications table
                                $stmt = $pdo->prepare("
                                    UPDATE notifications 
                                    SET title = ?, recipient_type = ?, message = ?
                                    WHERE notification_id = ?
                                ");
                                $stmt->execute([$title, $recipient_type, $message, $notification_id]);
                        
                                // Redirect to view notifications page
                                header('Location: dashboard.php?action=view_notifications');
                                exit();
                            }
                            break;
                        
                            case 'delete_notification':
                                if (!isset($_GET['id'])) {
                                    header('Location: dashboard.php?action=view_notifications');
                                    exit();
                                }
                            
                                $notification_id = $_GET['id'];
                            
                                // Fetch notification details
                                $stmt = $pdo->prepare("
                                    SELECT n.* 
                                    FROM notifications n 
                                    WHERE n.notification_id = ?
                                ");
                                $stmt->execute([$notification_id]);
                                $notification = $stmt->fetch();
                            
                                if (!$notification) {
                                    header('Location: dashboard.php?action=view_notifications');
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
                            
                                // Ensure the notification belongs to the logged-in CDO user
                                if ($notification['cdo_id'] !== $cdo_id) {
                                    die("You do not have permission to delete this notification.");
                                }
                            
                                // Delete from notifications table
                                $stmt = $pdo->prepare("DELETE FROM notifications WHERE notification_id = ?");
                                $stmt->execute([$notification_id]);
                            
                                // Redirect to view notifications page
                                header('Location: dashboard.php?action=view_notifications');
                                exit();
                                break;   

    case 'view':
    default:
        // Fetch the cdo_id of the logged-in CDO user
        $stmt = $pdo->prepare("SELECT cdo_id FROM cdo WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $cdo = $stmt->fetch();

        if (!$cdo) {
            die("CDO not found.");
        }

        $cdo_id = $cdo['cdo_id'];

        // Fetch all supervisors under this CDO
        $stmt = $pdo->prepare("
            SELECT s.*, u.username 
            FROM supervisors s 
            JOIN users u ON s.user_id = u.user_id 
            WHERE s.cdo_id = ?
        ");
        $stmt->execute([$cdo_id]);
        $supervisors = $stmt->fetchAll();
        break;
}
function uploadFile($file, $uploadDir) {
    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileName = uniqid() . '_' . basename($file['name']);
        $filePath = $uploadDir . '/' . $fileName;
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $filePath;
        }
    }
    return null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CDO Dashboard - Pratham Path</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'govt-blue': '#0a2463',
                        'govt-dark-blue': '#081f5c',
                        'govt-light-blue': '#e6f0ff',
                        'govt-orange': '#ff9933',
                        'govt-green': '#138808',
                        'govt-text-gray': '#4a5568',
                        'govt-red': '#e3342f'
                    }
                }
            }
        }
    </script>
    <style>
        .tricolor-border {
            background: linear-gradient(to right, #FF9933 33.33%, #FFFFFF 33.33%, #FFFFFF 66.66%, #138808 66.66%);
            height: 4px;
            width: 100%;
        }
    </style>
    <!-- <style>
        #notification-card {
            transition: all 0.3s ease;
            background-color: white; /* Ensure background color is white */
        }

        #notification-content {
            display: flex;
            align-items: center;
        }

        #notification-buttons {
            display: none; /* Hidden by default */
        }

        #notification-buttons a {
            width: 100%;
            text-align: center;
        }
    </style> -->
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <!-- Header with Tricolor border -->
    <header class="bg-govt-blue shadow-md">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <a href="../../client/index.php" class="flex items-center gap-2">
                <div class="text-white text-4xl font-semibold">
                    Pratham Path
                    <p class="text-xl">CDO Dashboard</p>
                </div>
            </a>

            <!-- Desktop Navigation (Hidden on mobile) -->
            <div class="hidden md:flex flex items-center justify-center gap-2 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                <a href="../logout.php" class="font-bold text-white text-xl hover:text-govt-light-blue">
                Logout
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="menu-toggle" class="md:hidden text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
        
        <!-- Mobile Navigation (Hidden by default) -->
        <div id="mobile-menu" class="hidden md:hidden bg-govt-blue mt-4 rounded-lg shadow-lg">
            <div class="px-4 py-4 flex text-white gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                <a href="../logout.php" class="block font-bold text-white text-xl hover:text-govt-light-blue">
                    Logout
                </a>
            </div>
        </div>

        <div class="tricolor-border mt-4"></div>
    </div>
</header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Supervisors Card -->
            <a href="?action=view" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow border-l-4 border-govt-blue">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-govt-light-blue rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-govt-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-blue">Supervisors</h2>
                        <p class="text-gray-600">View and manage Supervisors under you</p>
                    </div>
                </div>
            </a>

            <!-- Workers Card -->
            <a href="workers/view.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow border-l-4 border-govt-green">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-govt-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-green">Workers</h2>
                        <p class="text-gray-600">View Workers under your Supervisors</p>
                    </div>
                </div>
            </a>

            <!-- Reports Card -->
            <a href="reports/view.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow border-l-4 border-govt-orange">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-govt-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-orange">Reports</h2>
                        <p class="text-gray-600">View and generate reports</p>
                    </div>
                </div>
            </a>
            <!-- Notifications Card -->
            <a href="?action=view_notifications" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer border-l-4 border-govt-red">
    <!-- Default Content (Notifications Info) -->
                <div id="notification-content" class="flex items-center gap-4">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-govt-red h-8 w-8">
                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-1.29 1.29c-.63.63-.19 1.71.7 1.71h13.17c.89 0 1.34-1.08.71-1.71L18 16z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-red">Notifications</h2>
                        <p class="text-gray-600">View and add notifications for supervisor/worker</p>
                    </div>
                </div>

            </a>
        </div>

        <!-- Supervisor CRUD Section -->
        <?php if ($action === 'view'): ?>
            <div id="notification-buttons" class="flex justify-between items-end gap-4 mt-4">
                <h1 class="text-3xl font-bold text-govt-blue mb-6 mt-4">View Supervisors</h1>
                <a href="?action=add" class="bg-govt-blue text-white px-4 py-2 rounded-md text-center hover:bg-blue-600 mb-4 "> + Add Supervisor</a>           
            </div>
            <div class="bg-white rounded-lg shadow-md md:overflow-hidden overflow-x-scroll">
                <table class="min-w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aadhar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($supervisors as $supervisor): ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($supervisor['name']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($supervisor['username']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($supervisor['aadhar']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($supervisor['contact_number']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($supervisor['email']); ?></td>
                                <td class="px-6 py-4">
                                    <a href="?action=edit&id=<?php echo $supervisor['supervisor_id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <a href="?action=delete&id=<?php echo $supervisor['supervisor_id']; ?>" onclick="return confirmDelete();" class="text-red-500 hover:text-red-700 ml-2">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
             </div>
             <?php elseif ($action === 'add'): ?>
            <h1 class="text-3xl font-bold text-govt-blue mb-6 mt-4">Add Supervisor</h1>
            <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
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
        <label for="id_type" class="block text-sm font-medium text-gray-700">ID Type</label>
        <select name="id_type" id="id_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            <option value="" disabled selected>Select ID Type</option>
            <option value="Aadhar Card">Aadhar Card</option>
            <option value="PAN Card">PAN Card</option>
            <option value="Voter-ID Card">Voter-ID Card</option>
        </select>
    </div>
    <div class="mb-4">
        <label for="id_path" class="block text-sm font-medium text-gray-700">ID Document</label>
        <input type="file" name="id_path" id="id_path" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
    </div>
    <div class="mb-4">
        <label for="aadhar" class="block text-sm font-medium text-gray-700">Aadhar Number</label>
        <input type="text" name="aadhar" id="aadhar" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
    </div>
    <div class="mb-4">
        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
        <textarea name="address" id="address" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required></textarea>
    </div>
    <div class="mb-4">
        <label for="town_village" class="block text-sm font-medium text-gray-700">Town/Village</label>
        <input type="text" name="town_village" id="town_village" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
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
        <label for="qualification_type" class="block text-sm font-medium text-gray-700">Qualification Type</label>
        <select name="qualification_type" id="qualification_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            <option value="" disabled selected>Select Qualification</option>
            <option value="12th Pass">12th Pass</option>
            <option value="Graduate">Graduate</option>
            <option value="Post Graduate">Post Graduate</option>
            <option value="Phd">Phd</option>
        </select>
    </div>
    <div class="mb-4">
        <label for="qualification_path" class="block text-sm font-medium text-gray-700">Qualification Document</label>
        <input type="file" name="qualification_path" id="qualification_path" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
    </div>
    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Add Supervisor</button>
</form>
        <?php elseif ($action === 'edit'): ?>
            <h1 class="text-3xl font-bold text-govt-blue mb-6 mt-4">Edit Supervisor</h1>
            <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo htmlspecialchars($supervisor['name']); ?>" required>
    </div>
    <div class="mb-4">
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input type="text" name="username" id="username" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo htmlspecialchars($supervisor['username']); ?>" required>
    </div>
    <div class="mb-4">
        <label for="id_type" class="block text-sm font-medium text-gray-700">ID Type</label>
        <select name="id_type" id="id_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            <option value="Aadhar Card" <?php echo $supervisor['id_type'] === 'Aadhar Card' ? 'selected' : ''; ?>>Aadhar Card</option>
            <option value="PAN Card" <?php echo $supervisor['id_type'] === 'PAN Card' ? 'selected' : ''; ?>>PAN Card</option>
            <option value="Voter-ID Card" <?php echo $supervisor['id_type'] === 'Voter-ID Card' ? 'selected' : ''; ?>>Voter-ID Card</option>
        </select>
    </div>
    <div class="mb-4">
        <label for="id_path" class="block text-sm font-medium text-gray-700">ID Document</label>
        <input type="file" name="id_path" id="id_path" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
        <?php if ($supervisor['id_path']): ?>
            <p class="text-sm text-gray-500 mt-2">Current File: <a href="<?php echo $supervisor['id_path']; ?>" target="_blank" class="text-blue-500 hover:underline">View File</a></p>
        <?php endif; ?>
    </div>
    <div class="mb-4">
        <label for="aadhar" class="block text-sm font-medium text-gray-700">Aadhar Number</label>
        <input type="text" name="aadhar" id="aadhar" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo htmlspecialchars($supervisor['aadhar']); ?>" required>
    </div>
    <div class="mb-4">
        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
        <textarea name="address" id="address" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required><?php echo htmlspecialchars($supervisor['address']); ?></textarea>
    </div>
    <div class="mb-4">
        <label for="town_village" class="block text-sm font-medium text-gray-700">Town/Village</label>
        <input type="text" name="town_village" id="town_village" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo htmlspecialchars($supervisor['town_village']); ?>" required>
    </div>
    <div class="mb-4">
        <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
        <input type="text" name="contact_number" id="contact_number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo htmlspecialchars($supervisor['contact_number']); ?>" required>
    </div>
    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo htmlspecialchars($supervisor['email']); ?>" required>
    </div>
    <div class="mb-4">
        <label for="qualification_type" class="block text-sm font-medium text-gray-700">Qualification Type</label>
        <select name="qualification_type" id="qualification_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            <option value="12th Pass" <?php echo $supervisor['qualification_type'] === '12th Pass' ? 'selected' : ''; ?>>12th Pass</option>
            <option value="Graduate" <?php echo $supervisor['qualification_type'] === 'Graduate' ? 'selected' : ''; ?>>Graduate</option>
            <option value="Post Graduate" <?php echo $supervisor['qualification_type'] === 'Post Graduate' ? 'selected' : ''; ?>>Post Graduate</option>
            <option value="Phd" <?php echo $supervisor['qualification_type'] === 'Phd' ? 'selected' : ''; ?>>Phd</option>
        </select>
    </div>
    <div class="mb-4">
        <label for="qualification_path" class="block text-sm font-medium text-gray-700">Qualification Document</label>
        <input type="file" name="qualification_path" id="qualification_path" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
        <?php if ($supervisor['qualification_path']): ?>
            <p class="text-sm text-gray-500 mt-2">Current File: <a href="<?php echo $supervisor['qualification_path']; ?>" target="_blank" class="text-blue-500 hover:underline">View File</a></p>
        <?php endif; ?>
    </div>
    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Update Supervisor</button>
</form>
        <?php endif; ?>
        <!-- Notification CRUD Section -->
<?php if ($action === 'view_notifications'): ?>
    <div id="notification-buttons" class="flex justify-between items-end gap-4 mt-4">
        <h1 class="text-lg md:text-3xl font-bold text-govt-blue mb-6 mt-4">View Notifications</h1>
        <a href="?action=add_notification" class="bg-govt-blue text-white px-4 py-2 rounded-md text-center hover:bg-blue-600 mb-4 "> + Add Notifications</a>           
    </div>

    
    <div class="bg-white rounded-lg shadow-md overflow-x-scroll md:overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Target</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($notifications as $notification): ?>
                    <tr>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($notification['title']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($notification['message']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($notification['recipient_type']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($notification['created_at']); ?></td>
                        <td class="px-6 py-4">
                            <a href="?action=edit_notification&id=<?php echo $notification['notification_id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <a href="?action=delete_notification&id=<?php echo $notification['notification_id']; ?>" onclick="return confirmDeleteNotification();" class="text-red-500 hover:text-red-700 ml-2">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php elseif ($action === 'add_notification'): ?>
    <h1 class="text-3xl font-bold text-govt-blue mb-6 mt-4">Add Notification</h1>
    <form method="POST" class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" id="title" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
            <textarea name="message" id="message" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required></textarea>
        </div>
        <div class="mb-4">
            <label for="target" class="block text-sm font-medium text-gray-700">Target</label>
            <select name="target" id="target" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                <option value="" disabled selected>Select Target</option>
                <option value="Supervisor">Supervisor</option>
                <option value="Worker">Worker</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Add Notification</button>
    </form>
<?php elseif ($action === 'edit_notification'): ?>
    <h1 class="text-3xl font-bold text-govt-blue mb-6 mt-4">Edit Notification</h1>
    <form method="POST" class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" id="title" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo htmlspecialchars($notification['title']); ?>" required>
        </div>
        <div class="mb-4">
            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
            <textarea name="message" id="message" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required><?php echo htmlspecialchars($notification['message']); ?></textarea>
        </div>
        <div class="mb-4">
            <label for="target" class="block text-sm font-medium text-gray-700">Target</label>
            <select name="target" id="target" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                <option value="Supervisor" <?php echo $notification['recipient_type'] === 'Supervisor' ? 'selected' : ''; ?>>Supervisor</option>
                <option value="Worker" <?php echo $notification['recipient_type'] === 'Worker' ? 'selected' : ''; ?>>Worker</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Update Notification</button>
    </form>
<?php endif; ?>
    </main>
            
    <!-- Footer with Tricolor border -->
    <footer class="pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="pt-8 border-t border-gray-200 flex flex-col items-center justify-center text-center">
                <div class="mb-4 flex flex-col items-center">
                    <div class="w-16 h-full mb-2">
                        <img
                            src="../assets/image.png"
                            alt="National Emblem of India"
                            class="w-full h-full" />
                    </div>
                    <p class="text-sm text-govt-text-gray">Government of Assam</p>
                    <p class="text-sm text-govt-text-gray">
                        Department of School Education
                    </p>
                </div>

                <div class="mb-4">
                    <p class="text-xs text-govt-text-gray">
                        This site is designed, developed, and maintained by the IT Cell,
                        Department of School Education, Assam
                    </p>
                </div>

                <div
                    class="tricolor-border my-4 w-24 rounded-full h-1 bg-gradient-to-r from-orange-500 via-white to-green-500"></div>

                <div class="text-xs text-govt-text-gray">
                    <p class="mb-2">© 2024 Pratham Path. All rights reserved.</p>
                    <div class="flex justify-center space-x-4">
                        <a href="#" class="hover:text-govt-blue transition-colors">Privacy Policy</a>
                        <span>|</span>
                        <a href="#" class="hover:text-govt-blue transition-colors">Terms of Use</a>
                        <span>|</span>
                        <a href="#" class="hover:text-govt-blue transition-colors">Accessibility</a>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-xs text-govt-text-gray">
                        Last Updated: June 1, 2024
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
    </script>
    <script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this supervisor?");
    }
    </script>
    <script>
    function confirmDeleteNotification() {
        return confirm("Are you sure you want to delete this notification?");
    }
    </script>
    <!-- <script>
        document.getElementById('notification-card').addEventListener('click', function () {
            const content = document.getElementById('notification-content');
            const buttons = document.getElementById('notification-buttons');

            // Toggle visibility
            if (content.style.display !== 'none') {
                content.style.display = 'none'; // Hide the content
                buttons.classList.remove('hidden'); // Show the buttons
            } else {
                content.style.display = 'flex'; // Show the content
                buttons.classList.add('hidden'); // Hide the buttons
            }
        });
    </script> -->
</body>
</html>