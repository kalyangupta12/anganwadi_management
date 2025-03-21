<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/functions.php';
redirectIfNotSupervisor();

$action = isset($_GET['action']) ? $_GET['action'] : 'view';

$validActions = ['add_workers', 'edit_workers', 'delete_workers', 'view_workers', 'add_centres', 'edit_centres', 'delete_centres', 'view_centres', 'view_notifications', 'raise_issues', 'view_issues', 'edit_issues', 'delete_issues', 'add_reports', 'view_reports', 'edit_reports', 'delete_reports', 'log_visit', 'view_visit'];

if (!in_array($action, $validActions)) {
    $action = 'view_workers';
}
switch ($action) {
    case 'view_workers':
        // Fetch the cdo_id of the logged-in CDO user
        $stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $supervisor = $stmt->fetch();

        if (!$supervisor) {
            die("Supervisor not found.");
        }

        $supervisor_id = $supervisor['supervisor_id'];

        // Fetch all workers associated with centres created by supervisors belonging to this CDO.
        $stmt = $pdo->prepare("
            SELECT w.*, c.centre_name
            FROM workers AS w
            JOIN anganwadicentres AS c ON w.centre_id = c.centre_id
            JOIN supervisors AS s ON c.supervisor_id = s.supervisor_id
            WHERE s.supervisor_id = ?
        ");
        $stmt->execute([$supervisor_id]);
        $workers = $stmt->fetchAll();
        break;
    case 'add_workers':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitizeInput($_POST['name']);
            $username = sanitizeInput($_POST['username']);
            $password = hashPassword($_POST['password']);
            $gender = sanitizeInput($_POST['gender']);
            $id_type = sanitizeInput($_POST['id_type']);
            $aadhar = sanitizeInput($_POST['aadhar']);
            $emergency_contact = sanitizeInput($_POST['emergency_contact']);
            $contact_number = sanitizeInput($_POST['contact_number']);
            $email = sanitizeInput($_POST['email']);
            $centre_id = sanitizeInput($_POST['centre_id']);
            $qualification_type = sanitizeInput($_POST['qualification_type']);

            // Handle file uploads
            $id_path = uploadFile($_FILES['id_path'], '../uploads/worker_docs/id_docs');
            $qualification_path = uploadFile($_FILES['qualification_path'], '../uploads/worker_docs/qualification_docs');

            try {
                $pdo->beginTransaction();

                // Insert into users table
                $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'Worker')");
                $stmt->execute([$username, $password]);
                $user_id = $pdo->lastInsertId();

                if (!$user_id) {
                    throw new Exception("Failed to insert user.");
                }

                // Insert into workers table
                $stmt = $pdo->prepare("INSERT INTO workers (user_id, centre_id, name, gender, aadhar, emergency_contact, contact_number, email, id_type, id_path, qualification_type, qualification_path) 
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$user_id, $centre_id, $name, $gender, $aadhar, $emergency_contact, $contact_number, $email, $id_type, $id_path, $qualification_type, $qualification_path]);

                $pdo->commit();
                header('Location: dashboard.php?action=view_workers');
                exit();
            } catch (Exception $e) {
                $pdo->rollBack();
                die("Error: " . $e->getMessage());
            }
        }

        // Fetch Anganwadi Centres for dropdown
        $stmt = $pdo->prepare("SELECT * FROM anganwadicentres WHERE supervisor_id = (SELECT supervisor_id FROM supervisors WHERE user_id = ?)");
        $stmt->execute([$_SESSION['user_id']]);
        $centres = $stmt->fetchAll();
        break;

    case 'edit_workers':
        if (!isset($_GET['id'])) {
            header('Location: dashboard.php?action=view_workers');
            exit();
        }

        $worker_id = $_GET['id'];

        // Fetch worker details
        $stmt = $pdo->prepare("SELECT * FROM workers WHERE worker_id = ?");
        $stmt->execute([$worker_id]);
        $workers = $stmt->fetch();

        if (!$workers) {
            header('Location: dashboard.php?action=view_workers');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitizeInput($_POST['name']);
            $gender = sanitizeInput($_POST['gender']);
            $id_type = sanitizeInput($_POST['id_type']);
            $aadhar = sanitizeInput($_POST['aadhar']);
            $emergency_contact = sanitizeInput($_POST['emergency_contact']);
            $contact_number = sanitizeInput($_POST['contact_number']);
            $email = sanitizeInput($_POST['email']);
            $qualification_type = sanitizeInput($_POST['qualification_type']);
            $centre_id = sanitizeInput($_POST['centre_id']);
            // Handle file uploads
            $id_path = $workers['id_path'];
            $qualification_path = $workers['qualification_path'];

            if (!empty($_FILES['id_path']['name'])) {
                // Delete old ID document
                if (file_exists($id_path)) {
                    unlink($id_path);
                }
                $id_path = uploadFile($_FILES['id_path'], '../uploads/worker_docs/id_docs');
            }

            if (!empty($_FILES['qualification_path']['name'])) {
                // Delete old qualification document
                if (file_exists($qualification_path)) {
                    unlink($qualification_path);
                }
                $qualification_path = uploadFile($_FILES['qualification_path'], '../uploads/worker_focs/qualification_docs');
            }


            $stmt = $pdo->prepare("UPDATE workers SET name = ?, gender = ?, aadhar = ?, id_type = ?, id_path = ?, qualification_type = ?, qualification_path = ?, emergency_contact = ?, contact_number = ?, email = ?, centre_id = ? WHERE worker_id = ?");
            $stmt->execute([$name, $gender, $aadhar, $id_type, $id_path, $qualification_type, $qualification_path, $emergency_contact, $contact_number, $email, $centre_id, $worker_id]);

            header('Location: dashboard.php?action=view_workers');
            exit();
        }

        // Fetch Anganwadi Centres for dropdown
        $stmt = $pdo->prepare("SELECT * FROM anganwadicentres WHERE supervisor_id = (SELECT supervisor_id FROM supervisors WHERE user_id = ?)");
        $stmt->execute([$_SESSION['user_id']]);
        $centres = $stmt->fetchAll();
        break;
    case 'delete_workers':

        if (!isset($_GET['id'])) {
            header('Location: dashboard.php?action=view_workers');
            exit();
        }

        $worker_id = $_GET['id'];

        // Delete worker
        $stmt = $pdo->prepare("DELETE FROM workers WHERE worker_id = ?");
        $stmt->execute([$worker_id]);

        header('Location: dashboard.php?action=view_workers');
        exit();
        break;
    case 'view_centres':

        // Fetch the supervisor_id of the logged-in supervisor
        $stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $supervisor = $stmt->fetch();

        if (!$supervisor) {
            die("Supervisor not found.");
        }

        $supervisor_id = $supervisor['supervisor_id'];

        // Fetch all centres managed by this supervisor
        $stmt = $pdo->prepare("SELECT * FROM anganwadicentres WHERE supervisor_id = ?");
        $stmt->execute([$supervisor_id]);
        $centres = $stmt->fetchAll();
        break;

    case 'add_centres':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize inputs
            $centre_name = sanitizeInput($_POST['centre_name']);
            $location = sanitizeInput($_POST['location']);
            $contact_number = sanitizeInput($_POST['contact_number']);

            // Fetch the supervisor_id of the logged-in supervisor
            $stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $supervisor = $stmt->fetch();

            if (!$supervisor) {
                die("Supervisor not found.");
            }

            $supervisor_id = $supervisor['supervisor_id'];

            // Insert into anganwadicentres table
            $stmt = $pdo->prepare("INSERT INTO anganwadicentres (supervisor_id, centre_name, location, contact_number) VALUES (?, ?, ?, ?)");
            $stmt->execute([$supervisor_id, $centre_name, $location, $contact_number]);

            // Redirect to view page
            header('Location: dashboard.php?action=view_centres');
            exit();
        }
        break;
    case 'edit_centres':
        if (!isset($_GET['id'])) {
            header('Location: dashboard.php?action=view_centres');
            exit();
        }

        $centre_id = $_GET['id'];

        // Fetch centre details
        $stmt = $pdo->prepare("SELECT * FROM anganwadicentres WHERE centre_id = ?");
        $stmt->execute([$centre_id]);
        $centre = $stmt->fetch();

        if (!$centre) {
            header('Location: dashboard.php?action=view_centres');
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
            header('Location: dashboard.php?action=view_centres');
            exit();
        }

    case 'delete_centres':
        if (!isset($_GET['id'])) {
            header('Location: dashboard.php?action=view_centres');
            exit();
        }

        $centre_id = $_GET['id'];

        // Fetch centre details
        $stmt = $pdo->prepare("SELECT * FROM anganwadicentres WHERE centre_id = ?");
        $stmt->execute([$centre_id]);
        $centre = $stmt->fetch();

        if (!$centre) {
            header('Location: dashboard.php?action=view_centres');
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
        header('Location: dashboard.php?action=view_centres');
        exit();
        break;

    case 'view_notifications':
        // Fetch the supervisor_id of the logged-in supervisor
        $stmt = $pdo->prepare("SELECT supervisor_id, cdo_id FROM supervisors WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $supervisor = $stmt->fetch();

        if (!$supervisor) {
            die("Supervisor not found.");
        }

        $supervisor_id = $supervisor['supervisor_id'];
        $cdo_id = $supervisor['cdo_id'];
        // Fetch all notifications raised by the CDO of this supervisor
        $stmt = $pdo->prepare("SELECT * FROM notifications WHERE cdo_id = ?");
        $stmt->execute([$cdo_id]);
        $notifications = $stmt->fetchAll();
        break;

    case 'raise_issues':
        $stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $supervisor = $stmt->fetch();

        if (!$supervisor) {
            die("Supervisor not found.");
        }

        $supervisor_id = $supervisor['supervisor_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $issue_type = sanitizeInput($_POST['issue_type']);
            $issue_message = sanitizeInput($_POST['issue_message']);
            // Insert into issues table
            $stmt = $pdo->prepare("INSERT INTO supervisor_issues (supervisor_id, issue_type, issue_message) VALUES (?, ?, ?)");
            $stmt->execute([$supervisor_id, $issue_type, $issue_message]);

            // Redirect to view page
            header('Location: dashboard.php?action=view_issues');
            exit();
        }
        break;

    case 'view_issues':
        $stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $supervisor = $stmt->fetch();

        if (!$supervisor) {
            die("Supervisor not found.");
        }
        $supervisor_id = $supervisor['supervisor_id'];
        // Fetch all issues raised by the supervisor
        $stmt = $pdo->prepare("SELECT * FROM supervisor_issues WHERE supervisor_id = ?");
        $stmt->execute([$supervisor_id]);
        $issues = $stmt->fetchAll();
        break;

    case 'edit_issues':
        if (!isset($_GET['id'])) {
            header('Location: dashboard.php?action=view_issues');
            exit();
        }
        $stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $supervisor = $stmt->fetch();

        if (!$supervisor) {
            die("Supervisor not found.");
        }
        $issue_id = $_GET['id'];
        // Fetch issue details
        $stmt = $pdo->prepare("SELECT * FROM supervisor_issues WHERE issue_id = ? AND supervisor_id = ?");
        $stmt->execute([$issue_id, $supervisor['supervisor_id']]);
        $issue = $stmt->fetch();

        if (!$issue) {
            header('Location: dashboard.php?action=view_issues');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $issue_type = sanitizeInput($_POST['issue_type']);
            $issue_message = sanitizeInput($_POST['issue_message']);

            $stmt = $pdo->prepare("UPDATE supervisor_issues SET issue_type = ?, issue_message = ? WHERE issue_id = ?");
            $stmt->execute([$issue_type, $issue_message, $issue_id]);

            header('Location: dashboard.php?action=view_issues');
            exit();
        }
        break;

    case 'delete_issues':
        if (!isset($_GET['id'])) {
            header('Location: dashboard.php?action=view_issues');
            exit();
        }

        $issue_id = $_GET['id'];

        $stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $supervisor = $stmt->fetch();

        if (!$supervisor) {
            die("Supervisor not found.");
        }

        // Delete the issue
        $stmt = $pdo->prepare("DELETE FROM supervisor_issues WHERE issue_id = ? AND supervisor_id = ?");
        $stmt->execute([$issue_id, $supervisor['supervisor_id']]);

        header('Location: dashboard.php?action=view_issues');
        exit();
        break;

    case 'add_reports':
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize inputs
            $report_text = sanitizeInput($_POST['report_text']);

            // Handle file upload
            if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/supervisor_reports/'; // Absolute path to the uploads directory
                $file_name = uniqid() . '_' . basename($_FILES['report_file']['name']); // Unique file name
                $file_path = $upload_dir . $file_name; // Full path to save the file

                // Ensure the uploads directory exists
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true); // Create the directory if it doesn't exist
                }

                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES['report_file']['tmp_name'], $file_path)) {
                    // File uploaded successfully
                    $file_path = '/uploads/supervisor_reports/' . $file_name; // Relative path for database
                } else {
                    die("Failed to upload file.");
                }
            } else {
                die("No file uploaded or file upload error.");
            }

            // Fetch supervisor ID
            $stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $supervisor = $stmt->fetch();

            if (!$supervisor) {
                die("Supervisor not found.");
            }

            $supervisor_id = $supervisor['supervisor_id'];

            // Insert report into the database
            $stmt = $pdo->prepare("INSERT INTO supervisorreports (supervisor_id, report_text, file_path) VALUES (?, ?, ?)");
            $stmt->execute([$supervisor_id, $report_text, $file_path]);

            // Redirect to view reports page
            header('Location: dashboard.php?action=view_reports');
            exit();
        }
        break;

    case 'view_reports':
        $stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $supervisor = $stmt->fetch();

        if (!$supervisor) {
            die("Supervisor not found.");
        }
        $supervisor_id = $supervisor['supervisor_id'];
        // Fetch all reports raised by the supervisor
        $stmt = $pdo->prepare("SELECT * FROM supervisorreports WHERE supervisor_id = ?");
        $stmt->execute([$supervisor_id]);
        $reports = $stmt->fetchAll();
        break;

    case 'edit_reports':
        // Check if the report ID is provided
        if (!isset($_GET['id'])) {
            header('Location: dashboard.php?action=view_reports');
            exit();
        }

        $report_id = $_GET['id'];

        // Fetch the supervisor ID of the currently logged-in user
        $stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $supervisor = $stmt->fetch();

        if (!$supervisor) {
            die("Supervisor not found.");
        }

        $supervisor_id = $supervisor['supervisor_id'];

        // Fetch the existing report details and verify supervisor_id
        $stmt = $pdo->prepare("SELECT * FROM supervisorreports WHERE report_id = ? AND supervisor_id = ?");
        $stmt->execute([$report_id, $supervisor_id]);
        $report = $stmt->fetch();

        if (!$report) {
            die("Report not found or you do not have permission to edit this report.");
        }

        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize inputs
            $report_text = sanitizeInput($_POST['report_text']);

            // Handle file upload (if a new file is uploaded)
            $file_path = $report['file_path']; // Keep the existing file path by default
            if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] === UPLOAD_ERR_OK) {
                // Define the upload directory
                $upload_dir = '../uploads/supervisor_reports/';

                // Generate a unique file name using uniqid()
                $file_name = uniqid() . '_' . basename($_FILES['report_file']['name']);
                $new_file_path = $upload_dir . $file_name;

                // Ensure the uploads directory exists
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES['report_file']['tmp_name'], $new_file_path)) {
                    // Delete the old file if it exists
                    if (file_exists(__DIR__ . '/' . $report['file_path'])) {
                        unlink(__DIR__ . '/' . $report['file_path']);
                    }

                    // Update the file path for the database
                    $file_path = '/uploads/supervisor_reports/' . $file_name;
                } else {
                    die("Failed to upload file.");
                }
            }

            // Update the report in the database
            $stmt = $pdo->prepare("UPDATE supervisorreports SET report_text = ?, file_path = ? WHERE report_id = ? AND supervisor_id = ?");
            $stmt->execute([$report_text, $file_path, $report_id, $supervisor_id]);

            // Redirect to view reports page
            header('Location: dashboard.php?action=view_reports');
            exit();
        }
        break;

    case 'delete_reports':
        // Check if the report ID is provided
        if (!isset($_GET['id'])) {
            header('Location: dashboard.php?action=view_reports');
            exit();
        }

        $report_id = $_GET['id'];

        // Fetch the supervisor ID of the currently logged-in user
        $stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $supervisor = $stmt->fetch();

        if (!$supervisor) {
            die("Supervisor not found.");
        }

        $supervisor_id = $supervisor['supervisor_id'];

        // Fetch the report details and verify supervisor_id (to ensure the report belongs to the supervisor)
        $stmt = $pdo->prepare("SELECT * FROM supervisorreports WHERE report_id = ? AND supervisor_id = ?");
        $stmt->execute([$report_id, $supervisor_id]);
        $report = $stmt->fetch();

        if (!$report) {
            die("Report not found or you do not have permission to delete this report.");
        }

        // Delete the associated file (if it exists)
        if (!empty($report['file_path']) && file_exists(__DIR__ . '/' . $report['file_path'])) {
            unlink(__DIR__ . '/' . $report['file_path']); // Delete the file
        }

        // Delete the report from the database
        $stmt = $pdo->prepare("DELETE FROM supervisorreports WHERE report_id = ?");
        $stmt->execute([$report_id]);

        // Redirect to view reports page
        header('Location: dashboard.php?action=view_reports');
        exit();
        break;

    case 'log_visit':
        // Fetch the supervisor_id of the logged-in supervisor
        $stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $supervisor = $stmt->fetch();

        if (!$supervisor) {
            die("Supervisor not found.");
        }

        $supervisor_id = $supervisor['supervisor_id'];

        // Fetch the supervisor's assigned centres
        $stmt = $pdo->prepare("SELECT centre_id, centre_name FROM anganwadicentres WHERE supervisor_id = ?");
        $stmt->execute([$supervisor_id]);
        $centres = $stmt->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and validate inputs
            $visit_date = sanitizeInput($_POST['visit_date']);
            $visit_time = sanitizeInput($_POST['visit_time']);
            $notes = sanitizeInput($_POST['notes']);
            $centre_id = sanitizeInput($_POST['centre_id']);

            // Validate that the selected centre belongs to the supervisor
            $valid_centre = false;
            foreach ($centres as $centre) {
                if ($centre['centre_id'] == $centre_id) {
                    $valid_centre = true;
                    break;
                }
            }

            if (!$valid_centre) {
                die("Invalid centre selected.");
            }

            // Insert into visits table
            try {
                $stmt = $pdo->prepare("INSERT INTO visits (supervisor_id, visit_date, visit_time, notes, centre_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$supervisor_id, $visit_date, $visit_time, $notes, $centre_id]);

                // Redirect to view visits page
                header('Location: dashboard.php?action=view_visit');
                exit();
            } catch (PDOException $e) {
                die("Error logging visit: " . $e->getMessage());
            }
        }
        break;

    case 'view_visit':
        // Fetch the supervisor_id of the logged-in supervisor
        $stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $supervisor = $stmt->fetch();

        if (!$supervisor) {
            die("Supervisor not found.");
        }

        $supervisor_id = $supervisor['supervisor_id'];

        // Fetch all visits made by the supervisor
        $stmt = $pdo->prepare("SELECT v.*, c.centre_name FROM visits AS v JOIN anganwadicentres AS c ON v.centre_id = c.centre_id WHERE v.supervisor_id = ?");
        $stmt->execute([$supervisor_id]);
        $visits = $stmt->fetchAll();
        break;
    default:
        $stmt = $pdo->prepare("SELECT supervisor_id FROM supervisors WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $supervisor = $stmt->fetch();

        if (!$supervisor) {
            die("Supervisor not found.");
        }

        $supervisor_id = $supervisor['supervisor_id'];

        // Fetch all workers associated with centres created by supervisors belonging to this CDO.
        $stmt = $pdo->prepare("
            SELECT w.*, c.centre_name
            FROM workers AS w
            JOIN anganwadicentres AS c ON w.centre_id = c.centre_id
            JOIN supervisors AS s ON c.supervisor_id = s.supervisor_id
            WHERE s.supervisor_id = ?
        ");
        $stmt->execute([$supervisor_id]);
        $workers = $stmt->fetchAll();
        break;
}
function uploadFile($file, $uploadDir)
{
    if ($file['error'] === UPLOAD_ERR_OK) {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
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
    <title>Supervisor Dashboard - Pratham Path</title>
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
                    <p class="text-xl">Supervisor Dashboard</p>
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
    <div class="flex-grow container mx-auto px-4 py-8">
        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Workers Card -->
            <a href="?action=view_workers" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow border-l-4 border-govt-green">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-govt-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-green">Workers</h2>
                        <p class="text-gray-600">Add/Manage Workers</p>
                    </div>
                </div>
            </a>

            <!-- Centres Card -->
            <a href="?action=view_centres" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow border-l-4 border-govt-blue">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-govt-light-blue rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-govt-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-blue">Centres</h2>
                        <p class="text-gray-600">Add/Manage Centres</p>
                    </div>
                </div>
            </a>

            <!-- Notifications Card -->
            <a id="notification-card" href="?action=view_notifications" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer border-l-4 border-govt-red">
                <!-- Default Content (Notifications Info) -->
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-govt-red h-8 w-8">
                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-1.29 1.29c-.63.63-.19 1.71.7 1.71h13.17c.89 0 1.34-1.08.71-1.71L18 16z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-red">Notifications</h2>
                        <p class="text-gray-600">View the notifications given by your CDO</p>
                    </div>
                </div>
            </a>
            <!-- paste below -->
            <!-- Reports Card -->
            <a href="?action=view_reports" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow border-l-4 border-govt-orange">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-govt-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-orange">Reports</h2>
                        <p class="text-gray-600">View/Add the reports of Supervisor</p>
                    </div>
                </div>
            </a>

            <!-- Issues Card -->
            <a href="?action=view_issues" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer border-l-4 border-govt-red">
                <div id="notification-content" class="flex items-center gap-4">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 100 100" viewBox="0 0 100 100" id="attention" fill="currentColor" class="text-govt-red h-8 w-8" stroke-width="2">
                            <path d="M91.36,78.98L55.22,15.11c-1.08-1.91-3.03-3.05-5.22-3.05l0,0c-2.19,0-4.14,1.14-5.22,3.05L8.64,78.98
                        c-1.06,1.88-1.05,4.11,0.04,5.98c1.09,1.86,3.02,2.98,5.18,2.98h72.28c2.16,0,4.1-1.11,5.18-2.98
                        C92.41,83.1,92.43,80.86,91.36,78.98z M87.87,82.95c-0.17,0.3-0.69,0.99-1.73,0.99H13.86c-1.04,0-1.55-0.69-1.73-0.99
                        c-0.17-0.3-0.52-1.09-0.01-1.99l36.14-63.88c0.52-0.92,1.39-1.02,1.74-1.02c0.35,0,1.22,0.1,1.74,1.02l36.14,63.88
                        C88.39,81.86,88.04,82.65,87.87,82.95z"></path>
                            <path d="M50 63.94c-3.86 0-7 3.14-7 7s3.14 7 7 7 7-3.14 7-7S53.86 63.94 50 63.94zM50 73.94c-1.65 0-3-1.35-3-3s1.35-3 3-3 3 1.35 3 3S51.65 73.94 50 73.94zM50 31.94c-3.86 0-7 3.14-7 7v14c0 3.86 3.14 7 7 7s7-3.14 7-7v-14C57 35.08 53.86 31.94 50 31.94zM53 52.94c0 1.65-1.35 3-3 3s-3-1.35-3-3v-14c0-1.65 1.35-3 3-3s3 1.35 3 3V52.94z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-red">Issues</h2>
                        <p class="text-gray-600">View/Add the issues</p>
                    </div>
                </div>
            </a>

            <!-- Log Visit Card -->
            <a href="?action=view_visit" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer border-l-4 border-govt-blue">
                <div id="log-visit-content" class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-govt-blue h-8 w-8" stroke-width="2">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-blue">Log Visit</h2>
                        <p class="text-gray-600">Record and track supervisor visits</p>
                    </div>
                </div>
            </a>

            <!-- Children Card -->
            <a href="?action=view_children" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer border-l-4 border-govt-green">
                <div id="view-children-content" class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 458.502 458.502" xml:space="preserve" fill="currentColor" class="text-govt-green h-8 w-8">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g>
                                    <g>
                                        <g>
                                            <path d="M88.251,53.336c0,0.001-0.001,0.002-0.001,0.003c16.512,0,63.066,0.322,109.679,0.322c5.339,0,9.667-4.328,9.667-9.667 c0-5.339-4.328-9.668-9.667-9.668h-28.156c-5.996-13.72-18.17-24.384-32.854-27.408C137.695,3.175,133.552,0,128.437,0 c-5.135,0-9.247,3.187-8.484,6.911c-19.724,4.061-34.932,21.912-36.703,42.425c-0.089,1.029,0.258,2.048,0.957,2.809 C85.456,53.506,86.859,53.336,88.251,53.336z"></path>
                                            <path d="M426.175,290.22c-0.341-0.87-36.24-87.426-48.664-123.581c-8.058-23.447-21.569-35.336-40.157-35.336H277.18 c-18.588,0-32.099,11.889-40.157,35.336c-4.663,13.569-11.538,32.888-18.339,51.813l-6.931-59.48 c-0.706-22.473-19.263-40.572-41.876-40.572c-3.349,0-78.122,0-81.699,0c-22.614,0-41.17,18.099-41.876,40.572L31.243,288.194 c-1.131,9.712,5.825,18.503,15.537,19.635c9.749,1.13,18.506-5.854,19.634-15.537C81.081,166.426,81.911,158.896,81.946,158.52 l0.008,278.736c0,11.733,9.512,21.245,21.245,21.245c11.733,0,21.245-9.512,21.245-21.245v-149.19h9.173v149.191 c0,11.733,9.512,21.245,21.245,21.245c11.733,0,21.245-9.512,21.245-21.245c0-286.926-0.385-114.357-0.385-279.752 c0.518,1.077-0.819-8.838,15.916,134.788c1.051,9.017,8.702,15.629,17.563,15.657c12.405,0.039,18.311-11.937,19.312-14.676 c0.253-0.69,22.404-61.294,36.28-100.761l-23.585,121.771c-0.548,2.828,0.198,5.751,2.029,7.974 c1.832,2.223,4.563,3.52,7.443,3.52c3.741,0,8.229,0,13.253,0v111.479c0,11.51,9.331,20.841,20.841,20.841 s20.841-9.331,20.841-20.841v-111.48c2.996,0,6.002,0,8.998,0v111.479c0,11.51,9.331,20.841,20.841,20.841 s20.841-9.331,20.841-20.841V325.777c5.025,0,9.513,0,13.252,0c2.882,0,5.613-1.289,7.446-3.513s2.574-5.151,2.026-7.981 l-23.87-120.715c0,0,37.737,106.908,38.687,109.332c3.502,8.931,13.58,13.33,22.509,9.829 C425.278,309.228,429.677,299.15,426.175,290.22z"></path>
                                            <path d="M253.206,87.659c6.907,0,12.938-3.679,16.291-9.173c2.158,20.53,19.519,36.53,40.62,36.53s38.462-16,40.62-36.53 c3.353,5.493,9.384,9.173,16.291,9.173c10.545,0,18.61,0.967,25.861,17.885c13.535-26.863-9.184-56.072-25.861-56.072 c-8.232,0-15.226,5.219-17.908,12.521c-5.181-16.614-20.683-28.674-39.003-28.674c-18.32,0-33.823,12.06-39.003,28.674 c-2.682-7.302-9.676-12.521-17.908-12.521c-16.677,0-39.395,29.209-25.861,56.072C234.594,88.626,242.659,87.659,253.206,87.659z "></path>
                                            <path d="M128.437,101.482c18.368,0,33.903-12.125,39.042-28.807H89.394C94.534,89.357,110.068,101.482,128.437,101.482z"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-green">Children</h2>
                        <p class="text-gray-600">View and manage children details</p>
                    </div>
                </div>
            </a>

            <!-- Ration Card -->
            <a href="?action=view_ration" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer border-l-4 border-govt-orange">
                <div id="view-ration-content" class="flex items-center gap-4">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <svg viewBox="0 0 1024 1024" fill="currentColor" class="icon text-govt-orange h-8 w-8" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M166.38686 994.64729l-13.925472-139.937337c0-12.833278 16.382908-1.433504 37.066329-1.433505 20.546897 0 37.134591-11.399773 37.134591 1.433505l-11.60456 136.455969c0 29.830545-48.670889 17.679888-48.670888 3.481368z m-59.592828-455.513099C4.878693 493.057263 13.138409 420.562896 13.138409 420.562896c-12.833278 25.598293 14.471569 248.883674 120.755683 248.883674 47.510433 0.068262 87.44377-78.842744-27.10006-130.312379z m0-157.75375C4.878693 335.440037 13.138409 262.94567 13.138409 262.94567c-12.833278 25.530031 14.471569 248.883674 120.755683 248.883675 47.510433 0 87.44377-78.842744-27.10006-130.448904z m0-153.043663C4.878693 182.60116 13.138409 109.902007 13.138409 109.902007c-12.833278 25.598293 14.471569 248.815412 120.755683 248.815412 47.510433 0 87.44377-78.637957-27.10006-130.380641z m141.30258 441.178054c106.215852 0 133.452437-223.285381 120.755683-248.883674 0 0 8.259716 72.494367-93.792147 118.571295-114.407306 51.401373-74.610493 130.312379-26.963536 130.312379z m-141.30258 31.264049C4.878693 654.701953 13.138409 582.207586 13.138409 582.207586c-12.833278 25.598293 14.471569 248.883674 120.755683 248.883675 47.510433 0 87.44377-78.842744-27.10006-130.31238z m141.30258 130.31238c106.215852 0 133.452437-223.285381 120.755683-248.883675 0 0 8.259716 72.494367-93.792147 118.571295-114.407306 51.469635-74.610493 130.312379-26.963536 130.31238z m0-319.261916c106.215852 0 133.452437-223.353643 120.755683-248.883675 0 0 8.259716 72.494367-93.792147 118.434771-114.407306 51.60616-74.610493 130.448903-26.963536 130.448904z m0-153.111926c106.215852 0 133.452437-223.217119 120.755683-248.815412 0 0 8.259716 72.630891-93.792147 118.366508-114.407306 51.810946-74.610493 130.448903-26.963536 130.448904zM198.401792 240.282648C311.102545 240.282648 192.189939 0 192.189939 0c-14.335044 22.59476-106.488901 240.282648 6.211853 240.282648zM503.055615 1013.419372l67.989067-118.980868c5.392707-11.468035-14.403306-8.327978-32.970602-16.860743-18.840344-8.737551-28.943137-26.007866-34.267582-14.335044l-46.349976 132.633291c-6.007066 12.969802 32.970602 44.438637 45.599093 17.543364z m92.222118-455.649623c-72.90394-84.713286-34.950203-146.968335-34.950203-146.968336-22.458236 17.74815-91.607759 231.886408 4.573562 276.598094 43.141657 20.069062 112.632491-34.608893 30.376641-129.629758z m66.487301-143.213919c-72.90394-84.508499-34.950203-146.763549-34.950203-146.763549-22.458236 17.74815-91.744284 231.818145 4.5053 276.666355 43.209919 20.0008 112.632491-34.540631 30.444903-129.902806z m64.5077-138.640358C653.095746 191.543497 691.32253 129.015399 691.32253 129.015399c-22.526498 17.74815-91.744284 231.749883 4.573562 276.598093 43.141657 19.932538 112.495967-34.472369 30.376642-129.69802z m-57.613226 459.745351c96.317845 44.575162 215.093927-146.285714 214.274782-174.955803 0 0-23.004333 69.286048-134.954203 67.989067-125.534031-1.365242-122.530498 86.761149-79.320579 106.966736z m-141.370842-31.400574c-73.040464-84.645024-35.086728-146.831811-35.086727-146.831811-22.389974 17.74815-91.607759 231.818145 4.641823 276.393307 43.278181 20.069062 112.564229-34.472369 30.444904-129.561496z m73.24525 177.822812c96.249583 44.779948 215.093927-146.285714 214.343044-174.887541 0 0-23.072595 69.286048-134.954203 67.989068-125.534031-1.29698-122.530498 86.897673-79.388841 106.898473z m134.544631-289.499633c96.317845 44.711686 215.093927-146.422239 214.274781-174.887541 0 0-23.004333 69.217785-134.88594 67.989067-125.602293-1.365242-122.59876 86.965936-79.388841 106.898474z m64.507699-138.913406c96.317845 44.84821 215.025665-146.14919 214.20652-174.819279 0 0-23.140857 69.490834-134.885941 67.989068-125.534031-1.29698-122.462236 86.897673-79.320579 106.830211z m4.710086-128.40104c102.188387 47.442171 95.566962-220.486634 95.566962-220.486634-22.458236 14.608093-197.687088 172.976202-95.566962 220.486634z"></path></g></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-orange">Ration</h2>
                        <p class="text-gray-600">View and manage ration details</p>
                    </div>
                </div>
            </a>

        </div>

        <?php if ($action === 'view_workers'): ?>
            <div id="notification-buttons" class="flex justify-between items-end gap-4 mt-4">
                <h1 class="text-lg md:text-3xl font-bold text-govt-blue mb-6 mt-4">View Workers</h1>
                <a href="?action=add_workers" class="bg-govt-blue text-white px-4 py-2 rounded-md text-center hover:bg-blue-600 mb-4 "> + Add Workers</a>
            </div>


            <div class="bg-white rounded-lg shadow-md overflow-x-scroll">
                <table class="min-w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Id</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anganwadi Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gender</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Emergency Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aadhar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Proof Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Proof File</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qualification</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qualification File</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account Created On</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($workers as $workers): ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($workers['worker_id']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($workers['name']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($workers['centre_name']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($workers['gender']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($workers['email']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($workers['contact_number']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($workers['emergency_contact']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($workers['aadhar']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($workers['id_type']); ?></td>
                                <td class="px-6 py-4">
                                    <!-- fix URL in while hosting -->
                                    <?php if (!empty($workers['id_path'])): ?>
                                        <a href="<?php echo htmlspecialchars('/anganwadi_management/admin' . $workers['id_path']); ?>" target="_blank" class="text-blue-500 hover:text-blue-700">View PDF</a>
                                    <?php else: ?>
                                        <span class="text-gray-400">No File</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($workers['qualification_type']); ?></td>
                                <td class="px-6 py-4">
                                    <!-- fix URL in while hosting -->
                                    <?php if (!empty($workers['qualification_path'])): ?>
                                        <a href="<?php echo htmlspecialchars('/anganwadi_management/admin' . $workers['qualification_path']); ?>" target="_blank" class="text-blue-500 hover:text-blue-700">View PDF</a>
                                    <?php else: ?>
                                        <span class="text-gray-400">No File</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($workers['created_at']); ?></td>
                                <td class="px-6 py-4">
                                    <a href="?action=edit_workers&id=<?php echo $workers['worker_id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <a href="?action=delete_workers&id=<?php echo $workers['worker_id']; ?>" onclick="return confirmWorkerDelete();" class="text-red-500 hover:text-red-700 ml-2">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($action === 'add_workers'): ?>
            <h1 class="text-3xl font-bold text-govt-blue mb-6 mt-4">Add Workers</h1>
            <br>
            <form method="POST" class="bg-white p-6 rounded-lg shadow-md" enctype="multipart/form-data">
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
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" id="gender" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
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
                <div class="mb-4">
                    <label for="emergency_contact" class="block text-sm font-medium text-gray-700">Emergency Contact</label>
                    <input type="text" name="emergency_contact" id="emergency_contact" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
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
                    <label for="centre_id" class="block text-sm font-medium text-gray-700">Anganwadi Centre</label>
                    <select name="centre_id" id="centre_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                        <?php foreach ($centres as $centre): ?>
                            <option value="<?php echo $centre['centre_id']; ?>"><?php echo $centre['centre_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Add Worker</button>
            </form>
        <?php elseif ($action === 'edit_workers'): ?>
            <h1 class="text-3xl font-bold text-govt-blue mb-6 mt-4">Edit Workers</h1>
            <form method="POST" class="bg-white p-6 rounded-lg shadow-md">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo $workers['name']; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" id="gender" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                        <option value="Male" <?php echo $workers['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $workers['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo $workers['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="id_type" class="block text-sm font-medium text-gray-700">ID Type</label>
                    <select name="id_type" id="id_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                        <option value="Aadhar Card" <?php echo $workers['id_type'] === 'Aadhar Card' ? 'selected' : ''; ?>>Aadhar Card</option>
                        <option value="PAN Card" <?php echo $workers['id_type'] === 'PAN Card' ? 'selected' : ''; ?>>PAN Card</option>
                        <option value="Voter-ID Card" <?php echo $workers['id_type'] === 'Voter-ID Card' ? 'selected' : ''; ?>>Voter-ID Card</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="id_path" class="block text-sm font-medium text-gray-700">ID Document</label>
                    <input type="file" name="id_path" id="id_path" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    <?php if ($workers['id_path']): ?>
                        <p class="text-sm text-gray-500 mt-2">Current File: <a href="<?php echo htmlspecialchars('/anganwadi_management/admin' . $workers['id_path']); ?>" target="_blank" class="text-blue-500 hover:underline">View File</a></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label for="aadhar" class="block text-sm font-medium text-gray-700">Aadhar Number</label>
                    <input type="text" name="aadhar" id="aadhar" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo $workers['aadhar']; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="qualification_type" class="block text-sm font-medium text-gray-700">Qualification Type</label>
                    <select name="qualification_type" id="qualification_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                        <option value="12th Pass" <?php echo $workers['qualification_type'] === '12th Pass' ? 'selected' : ''; ?>>12th Pass</option>
                        <option value="Graduate" <?php echo $workers['qualification_type'] === 'Graduate' ? 'selected' : ''; ?>>Graduate</option>
                        <option value="Post Graduate" <?php echo $workers['qualification_type'] === 'Post Graduate' ? 'selected' : ''; ?>>Post Graduate</option>
                        <option value="Phd" <?php echo $workers['qualification_type'] === 'Phd' ? 'selected' : ''; ?>>Phd</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="qualification_path" class="block text-sm font-medium text-gray-700">Qualification Document</label>
                    <input type="file" name="qualification_path" id="qualification_path" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    <?php if ($workers['qualification_path']): ?>
                        <p class="text-sm text-gray-500 mt-2">Current File: <a href="<?php echo htmlspecialchars('/anganwadi_management/admin' . $workers['qualification_path']); ?>" target="_blank" class="text-blue-500 hover:underline">View File</a></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label for="emergency_contact" class="block text-sm font-medium text-gray-700">Emergency Contact</label>
                    <input type="text" name="emergency_contact" id="emergency_contact" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo $workers['emergency_contact']; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                    <input type="text" name="contact_number" id="contact_number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo $workers['contact_number']; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="<?php echo $workers['email']; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="centre_id" class="block text-sm font-medium text-gray-700">Anganwadi Centre</label>
                    <select name="centre_id" id="centre_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                        <?php foreach ($centres as $centre): ?>
                            <option value="<?php echo $centre['centre_id']; ?>" <?php echo $centre['centre_id'] === $workers['centre_id'] ? 'selected' : ''; ?>><?php echo $centre['centre_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Update Worker</button>
            </form>
        <?php elseif ($action === 'view_centres'): ?>
    <div id="notification-buttons" class="flex justify-between items-end gap-4 mt-4">
        <h1 class="text-lg md:text-3xl font-bold text-govt-blue mb-6 mt-4">View Anganwadi Centres</h1>
        <div class="flex gap-4 ">
            <form method="GET" action="dashboard.php" class="flex items-center gap-2 mb-4">
                <input type="hidden" name="action" value="view_centres">
                <input type="text" name="search" placeholder="Search Centres" class="px-4 py-2 border border-gray-300 rounded-md">
                <button type="submit" class="bg-govt-blue text-white px-4 py-2 rounded-md hover:bg-blue-600 ml-2">Search</button>
            </form>
            <a href="?action=add_centres" class="bg-govt-blue text-white px-4 py-2 rounded-md text-center hover:bg-blue-600 mb-4">+ Add Anganwadi Centres</a>
        </div>
    </div>

    <?php
    $search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
    $stmt = $pdo->prepare("SELECT * FROM anganwadicentres WHERE supervisor_id = ? AND centre_name LIKE ?");
    $stmt->execute([$supervisor_id, "%$search%"]);
    $centres = $stmt->fetchAll();
    ?>

    <div class="bg-white rounded-lg shadow-md md:overflow-hidden overflow-x-scroll">
        <table class="min-w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Id</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anganwadi Centre Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account Created On</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($centres as $centres): ?>
                    <tr>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($centres['centre_id']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($centres['centre_name']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($centres['location']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($centres['contact_number']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($centres['created_at']); ?></td>
                        <td class="px-6 py-4">
                            <a href="?action=edit_centres&id=<?php echo $centres['centre_id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <a href="?action=delete_centres&id=<?php echo $centres['centre_id']; ?>" onclick="return confirmDelete();" class="text-red-500 hover:text-red-700 ml-2">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
        <?php elseif ($action === 'add_centres'): ?>
            <h1 class="text-3xl font-bold text-govt-blue mb-6 mt-4">Add Centres</h1>
            <form method="POST" class="bg-white p-6 rounded-lg shadow-md">
                <div class="mb-4">
                    <label for="centre_name" class="block text-sm font-medium text-gray-700">Centre Name</label>
                    <input type="text" name="centre_name" id="centre_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" name="location" id="location" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                    <input type="text" name="contact_number" id="contact_number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Add Centre</button>
            </form>

        <?php elseif ($action === 'edit_centres'): ?>
            <h1 class="text-3xl font-bold text-govt-blue mb-6 mt-4">Edit Centres</h1>
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
        <?php elseif ($action === 'view_notifications'): ?>
            <div id="notification-buttons" class="flex justify-between items-end gap-4 mt-4">
                <h1 class="text-lg md:text-3xl font-bold text-govt-blue mb-6 mt-4">View Notifications</h1>
            </div>


            <div class="bg-white rounded-lg shadow-md md:overflow-hidden overflow-x-scroll">
                <table class="min-w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notification Id</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recipient Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($notifications as $notifications): ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($notifications['notification_id']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($notifications['title']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($notifications['recipient_type']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($notifications['message']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($notifications['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($action === 'raise_issues'): ?>
            <h1 class="text-3xl font-bold text-govt-blue mb-6 mt-4">Raise an Issue</h1>
            <form method="POST" class="bg-white p-6 rounded-lg shadow-md">
                <div class="mb-4">
                    <label for="issue_type" class="block text-sm font-medium text-gray-700">Issue Related To</label>
                    <select name="issue_type" id="issue_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                        <option value="children">Children</option>
                        <option value="ration">Ration</option>
                        <option value="worker">Worker</option>
                        <option value="anganwadi">Anganwadi</option>
                        <option value="other">other</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="issue_message" class="block text-sm font-medium text-gray-700">Describe the Issue</la>
                        <textarea name="issue_message" id="issue_message" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required></textarea>
                </div>
                <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-400">Raise Issue</button>
            </form>
        <?php elseif ($action === 'view_issues'): ?>
            <div id="notification-buttons" class="flex justify-between items-end gap-4 mt-4">
                <h1 class="text-lg md:text-3xl font-bold text-govt-blue mb-6 mt-4">View Issues</h1>
                <a href="?action=raise_issues" class="bg-govt-blue text-white px-4 py-2 rounded-md text-center hover:bg-blue-600 mb-4 "> + Raise an Issue</a>
            </div>
            <div class="bg-white rounded-lg shadow-md md:overflow-hidden overflow-x-scroll">
                <table class="min-w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issue Id</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issue Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issue Message</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Viewed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted On</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($issues as $issues): ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($issues['issue_id']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($issues['issue_type']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($issues['issue_message']); ?></td>
                                <?php if ($issues['viewed'] == 0): ?>
                                    <td class="px-6 py-4 text-govt-orange">Pending</td>
                                <?php else: ?>
                                    <td class="px-6 py-4 text-govt-green">Viewed</td>
                                <?php endif; ?>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($issues['created_at']); ?></td>
                                <td class="px-6 py-4">
                                    <a href="?action=edit_issues&id=<?php echo $issues['issue_id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <a href="?action=delete_issues&id=<?php echo $issues['issue_id']; ?>" onclick="return confirmIssueDelete();" class="text-red-500 hover:text-red-700 ml-2">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($action === 'edit_issues'): ?>
            <h1 class="text-3xl font-bold text-govt-blue mb-6 mt-4">Edit an Issue</h1>
            <form method="POST" class="bg-white p-6 rounded-lg shadow-md">
                <div class="mb-4">
                    <label for="issue_type" class="block text-sm font-medium text-gray-700">Issue Related To</label>
                    <select name="issue_type" id="issue_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                        <option value="children" <?php echo ($issue['issue_type'] === 'children') ? 'selected' : ''; ?>>Children</option>
                        <option value="ration" <?php echo ($issue['issue_type'] === 'ration') ? 'selected' : ''; ?>>Ration</option>
                        <option value="worker" <?php echo ($issue['issue_type'] === 'worker') ? 'selected' : ''; ?>>Worker</option>
                        <option value="anganwadi" <?php echo ($issue['issue_type'] === 'anganwadi') ? 'selected' : ''; ?>>Anganwadi</option>
                        <option value="other" <?php echo ($issue['issue_type'] === 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="issue_message" class="block text-sm font-medium text-gray-700">Describe the Issue</label>
                    <textarea name="issue_message" id="issue_message" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required><?php echo htmlspecialchars($issue['issue_message']); ?></textarea>
                </div>
                <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-400">Update Issue</button>
            </form>
        <?php elseif ($action === 'view_reports'): ?>
            <div id="notification-buttons" class="flex justify-between items-end gap-4 mt-4">
                <h1 class="text-lg md:text-3xl font-bold text-govt-blue mb-6 mt-4">View Reports</h1>
                <a href="?action=add_reports" class="bg-govt-blue text-white px-4 py-2 rounded-md text-center hover:bg-blue-600 mb-4 "> + Add Reports</a>
            </div>
            <div class="bg-white rounded-lg shadow-md md:overflow-hidden overflow-x-scroll">
                <table class="min-w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Report Id</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Report Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Report File</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Viewed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted On</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($reports as $reports): ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($reports['report_id']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($reports['report_text']); ?></td>
                                <td class="px-6 py-4">
                                    <!-- fix URL in while hosting -->
                                    <?php if (!empty($reports['file_path'])): ?>
                                        <a href="<?php echo htmlspecialchars('/anganwadi_management/admin' . $reports['file_path']); ?>" target="_blank" class="text-blue-500 hover:text-blue-700">View PDF</a>
                                    <?php else: ?>
                                        <span class="text-gray-400">No File</span>
                                    <?php endif; ?>
                                </td>
                                <?php if ($reports['verified'] == 0): ?>
                                    <td class="px-6 py-4 text-govt-orange">Pending</td>
                                <?php else: ?>
                                    <td class="px-6 py-4 text-govt-green">Viewed</td>
                                <?php endif; ?>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($reports['created_at']); ?></td>
                                <td class="px-6 py-4">
                                    <a href="?action=edit_reports&id=<?php echo $reports['report_id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <a href="?action=delete_reports&id=<?php echo $reports['report_id']; ?>" onclick="return confirmIssueDelete();" class="text-red-500 hover:text-red-700 ml-2">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($action === 'add_reports'): ?>
            <h1 class="text-3xl font-bold text-govt-blue mb-6 mt-4">Add Report</h1>
            <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
                <!-- Report Text -->
                <div class="mb-4">
                    <label for="report_text" class="block text-sm font-medium text-gray-700">Report Text</label>
                    <textarea name="report_text" id="report_text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required></textarea>
                </div>

                <!-- File Upload -->
                <div class="mb-4">
                    <label for="report_file" class="block text-sm font-medium text-gray-700">Upload File</label>
                    <input type="file" name="report_file" id="report_file" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-400">Submit Report</button>
            </form>
        <?php elseif ($action === 'edit_reports'): ?>
            <h1 class="text-3xl font-bold text-govt-blue mb-6 mt-4">Edit Report</h1>
            <form method="POST" action="dashboard.php?action=edit_reports&id=<?php echo $report['report_id']; ?>" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
                <!-- Report Text -->
                <div class="mb-4">
                    <label for="report_text" class="block text-sm font-medium text-gray-700">Report Text</label>
                    <textarea name="report_text" id="report_text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required><?php echo htmlspecialchars($report['report_text']); ?></textarea>
                </div>

                <!-- File Upload -->
                <div class="mb-4">
                    <label for="report_file" class="block text-sm font-medium text-gray-700">Upload New File (Optional)</label>
                    <input type="file" name="report_file" id="report_file" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    <p class="text-sm text-gray-500 mt-2">Current file: <a href="/anganwadi_management/admin/<?php echo $report['file_path']; ?>" target="_blank" class="text-blue-500"><?php echo basename($report['file_path']); ?></a></p>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-400">Update Report</button>
            </form>
        <?php elseif ($action === 'log_visit'): ?>
            <h1 class="text-3xl font-bold text-govt-blue mb-6 mt-4">Log a Visit</h1>
            <form method="POST" class="bg-white p-6 rounded-lg shadow-md">
                <!-- Visit Date -->
                <div class="mb-4">
                    <label for="visit_date" class="block text-sm font-medium text-gray-700">Visit Date</label>
                    <input type="date" name="visit_date" id="visit_date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                </div>

                <!-- Visit Time -->
                <div class="mb-4">
                    <label for="visit_time" class="block text-sm font-medium text-gray-700">Visit Time</label>
                    <input type="time" name="visit_time" id="visit_time" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                </div>

                <!-- Notes -->
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" rows="4" placeholder="Enter visit notes..."></textarea>
                </div>

                <!-- Centre Selection -->
                <div class="mb-4">
                    <label for="centre_id" class="block text-sm font-medium text-gray-700">Select Centre</label>
                    <select name="centre_id" id="centre_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                        <option value="">Select a Centre</option>
                        <?php foreach ($centres as $centre): ?>
                            <option value="<?php echo htmlspecialchars($centre['centre_id']); ?>"><?php echo htmlspecialchars($centre['centre_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" class="bg-govt-blue text-white px-4 py-2 rounded-md hover:bg-govt-dark-blue transition-colors">Log Visit</button>
                </div>
            </form>
        <?php elseif ($action === 'view_visit'): ?>
            <div id="notification-buttons" class="flex justify-between items-end gap-4 mt-4">
                <h1 class="text-lg md:text-3xl font-bold text-govt-blue mb-6 mt-4">View Visits</h1>
                <a href="?action=log_visit" class="bg-govt-blue text-white px-4 py-2 rounded-md text-center hover:bg-blue-600 mb-4 "> + Log a visit</a>
            </div>
            <div class="bg-white rounded-lg shadow-md md:overflow-hidden overflow-x-scroll">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visit Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visit Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Centre Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($visits)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No visits found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($visits as $visit): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($visit['visit_date']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($visit['visit_time']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($visit['centre_name']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($visit['notes']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            </main>
        <?php endif; ?>


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
                return confirm("Are you sure you want to delete this Anganwadi Centre?");
            }

            function confirmWorkerDelete() {
                return confirm("Are you sure you want to delete this Worker?");
            }

            function confirmIssueDelete() {
                return confirm("Are you sure you want to delete this Issue?");
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