<?php
// worker/dashboard.php

require '../includes/auth.php';
require '../includes/db.php';
require '../includes/functions.php';
redirectIfNotWorker();

// Set the default timezone to India
date_default_timezone_set('Asia/Kolkata');
$user_id = $_SESSION['user_id'];

// Fetch worker's assigned centre
$stmt = $pdo->prepare("SELECT * FROM workers WHERE user_id = ?");
$stmt->execute([$user_id]);
$worker = $stmt->fetch();

if (!$worker) {
    header('Location: ../login.php');
    exit();
}

$centre_id = $worker['centre_id'];
$worker_id = $worker['worker_id'];

// Fetch children for the worker's centre
$stmt = $pdo->prepare("SELECT * FROM children WHERE centre_id = ?");
$stmt->execute([$centre_id]);
$children = $stmt->fetchAll(PDO::FETCH_ASSOC); // Use fetchAll() to get all rows

$stmt = $pdo->prepare("SELECT * FROM children 
WHERE (image_path IS NULL OR image_path = '') 
AND centre_id = ?");
$stmt->execute([$centre_id]);
$children_without_face = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle actions using switch-case
$action = $_GET['action'] ?? 'dashboard'; // Default action is 'dashboard'

switch ($action) {
    case 'register_child':
        handleRegisterChild();
        break;
    case 'mark_attendance':
        handleMarkAttendance();
        break;
    case 'register_face':
        handleRegisterFace();
        break;
    case 'attendance_reports':
        handleAttendanceReports();
        break;
    case 'distribute_ration':
        handleRationDistribution();
        break;
    case 'get_ration_history':
        handleGetRationHistory();
        break;
    default:
        // Show the dashboard
        break;
}

function handleRationDistribution() {
    global $pdo, $centre_id, $worker_id;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $child_id = $_POST['child_id'];
        $ration_type = $_POST['ration_type'];
        $quantity = $_POST['quantity'];
        $datetime = $_POST['distribution_date'] ? $_POST['distribution_date'] . ' ' . date('H:i:s') : date('Y-m-d H:i:s');

        // Insert ration distribution record
        $stmt = $pdo->prepare("
            INSERT INTO rationdistribution (centre_id, child_id, datetime, ration_type, quantity, distributed_by)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        if ($stmt->execute([$centre_id, $child_id, $datetime, $ration_type, $quantity, $worker_id])) {
            $_SESSION['success_message'] = "Ration distribution recorded successfully";
        } else {
            $_SESSION['error_message'] = "Failed to record ration distribution";
        }

        header('Location: dashboard.php?action=dashboard#tab-ration');
        exit();
    }
}

function handleGetRationHistory() {
    global $pdo, $centre_id;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $date = $_POST['date'] ?? date('Y-m-d');
        
        $stmt = $pdo->prepare("
            SELECT r.*, c.name AS child_name 
            FROM rationdistribution r 
            JOIN children c ON r.child_id = c.child_id 
            WHERE r.centre_id = ? 
            AND DATE(r.datetime) = ?
            ORDER BY r.datetime DESC
        ");
        $stmt->execute([$centre_id, $date]);
        $rations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rations);
        exit();
    }
}

function handleRegisterChild() {
    global $pdo, $centre_id;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $aadhar_card = $_POST['aadhar_card'];
        $aadhar_type = $_POST['aadhar_type'];
        $parent_name = $_POST['parent_name'];
        $contact_number = $_POST['contact_number'];

        // Fetch centre name and location
        $stmt = $pdo->prepare("SELECT centre_name, location FROM anganwadicentres WHERE centre_id = ?");
        $stmt->execute([$centre_id]);
        $centre = $stmt->fetch();

        if (!$centre) {
            echo json_encode(['status' => 'error', 'message' => 'Centre not found']);
            exit();
        }

        // Extract first 4 uppercase letters from centre name & location
        $centre_code = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $centre['centre_name']), 0, 4));
        $location_code = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $centre['location']), 0, 4));

        // Find the highest existing child_id for this centre
        $stmt = $pdo->prepare("SELECT child_id FROM children WHERE centre_id = ? ORDER BY child_id DESC LIMIT 1");
        $stmt->execute([$centre_id]);
        $last_child = $stmt->fetch();
        
        if ($last_child) {
            preg_match('/(\d+)$/', $last_child['child_id'], $matches);
            $next_number = str_pad((int)$matches[1] + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $next_number = '001';
        }

        // Generate child_id in the format TEST-KGSD-001
        $child_id = "{$centre_code}-{$location_code}-{$next_number}";

        // Insert child data into the database
        $stmt = $pdo->prepare("
            INSERT INTO children (child_id, centre_id, name, dob, gender, aadhar_card, aadhar_type, parent_name, contact_number)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $child_id, $centre_id, $name, $dob, $gender, $aadhar_card, $aadhar_type, $parent_name, $contact_number
        ]);

        header('Location: dashboard.php?action=dashboard');
        exit();
    }
}

// Function to handle attendance marking
function handleMarkAttendance() {
    global $pdo, $centre_id, $worker_id; // Added $worker_id here

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $child_id = $_POST['child_id'];
        $datetime = date('Y-m-d H:i:s'); // Current timestamp in India timezone
        $status = 'present'; // Default status

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

        // Insert attendance record - use $worker_id directly
        $stmt = $pdo->prepare("
            INSERT INTO attendance (centre_id, worker_id, child_id, datetime, status, marked_by)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        if ($stmt->execute([$centre_id, $worker_id, $child_id, $datetime, $status, $worker_id])) {
            echo json_encode(['status' => 'success', 'message' => 'Attendance marked successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to mark attendance']);
        }
        exit();
    }
}
// Function to handle face registration
function handleRegisterFace() {
    global $pdo;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $child_id = $_POST['child_id'];
        $image_data = $_POST['image_data'];

        // Save the image to the server
        $upload_dir = '../uploads/children/';
        $image_name = uniqid() . '.png'; // Save as PNG
        $image_path = $upload_dir . $image_name;

        // Convert base64 data to image file
        list($type, $data) = explode(';', $image_data);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);

        file_put_contents($image_path, $data);

        // Update the child's record with the face image path
        $stmt = $pdo->prepare("
            UPDATE children SET image_path = ? WHERE child_id = ?
        ");
        if ($stmt->execute([$image_path, $child_id])) {
            echo json_encode(['status' => 'success', 'message' => 'Face registered successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to register face']);
        }
        exit();
    }
}

// Function to handle attendance reports - Fixed: moved outside handleRegisterFace
function handleAttendanceReports() {
    global $pdo, $centre_id;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $date = $_POST['date'];
        $stmt = $pdo->prepare("
            SELECT a.*, c.name AS child_name 
            FROM attendance a 
            JOIN children c ON a.child_id = c.child_id 
            WHERE a.centre_id = ? AND DATE(a.datetime) = ?
        ");
        $stmt->execute([$centre_id, $date]);
        $attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($attendance);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anganwadi Worker Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="face-api.min.js"></script>
    <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'govt-blue': '#00008B',
            'govt-orange': '#FF9933',
            'govt-green': '#138808',
            'govt-white': '#FFFFFF',
            'govt-light-blue': '#E6F3FF',
            'govt-dark-blue': '#001D6E',
            'govt-light-gray': '#F1F1F1',
            'govt-text-gray': '#444444',
            'govt-red': '#FF0000'
          }
        }
      }
    }
  </script>
  <style>
    .capturedPhotoContainer {
      position: relative;
      width: 640px;
      height: 480px;
    }
    .webcamContainer {
      position: relative;
      width: 640px;
      height: 480px;
    }
    .tricolor-border {
      background: linear-gradient(to right, #FF9933 33.33%, #FFFFFF 33.33%, #FFFFFF 66.66%, #138808 66.66%);
      height: 4px;
      width: 100%;
    }
    
    /* Tab styles */
    .tabs {
      display: flex;
      flex-wrap: wrap;
    }
    
    .tab-label {
      order: 1;
      display: block;
      cursor: pointer;
      transition: background ease 0.2s;
      flex: 1;
      text-align: center;
    }
    
    .tab-content {
      order: 99;
      flex-grow: 1;
      width: 100%;
      display: none;
    }
    
    .tab-input {
      position: absolute;
      opacity: 0;
    }
    
    .tab-input:checked + .tab-label {
      background: #f8fafc;
      color: #00008B;
      border-bottom: 2px solid #00008B;
      z-index: 1;
    }
    
    .tab-input:checked + .tab-label + .tab-content {
      display: block;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <!-- Header -->
   <header class="bg-govt-blue shadow-md">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <a href="../../client/index.php" class="flex items-center gap-2">
                <div class="text-white text-4xl font-semibold">
                    Pratham Path
                    <p class="text-xl">Worker Dashboard</p>
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
    <main class="flex-grow container mx-auto px-4 py-6">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h1 class="text-2xl font-bold text-govt-blue mb-2">Worker Dashboard</h1>
            <p class="text-gray-600">Manage children records, attendance, and ration distribution</p>
        </div>

        <!-- Tabs -->
        <div class="tabs w-full">
            <!-- Children Details Tab -->
            <input type="radio" name="tabs" id="tab-children" class="tab-input" checked>
            <label for="tab-children" class="tab-label text-lg py-3 w-1/3 text-center">Children Details</label>
            <div class="tab-content p-4">
                <!-- Children Details Content -->
                <div class="space-y-6">
                    <!-- Register New Child Form -->
                    <div class="mb-6 border-govt-blue border-t-4 bg-white shadow-md rounded-lg">
                        <div class="p-4 border-b">
                            <h2 class="text-xl font-bold text-govt-blue">Register New Child</h2>
                        </div>
                        <div class="p-6">
                            <form method="POST" action="?action=register_child" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Add form fields for child registration -->
                                <div>
                                    <label class="block text-sm font-medium mb-1">Full Name</label>
                                    <input type="text" name="name" placeholder="Full Name" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" required />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Date of Birth</label>
                                    <input type="date" name="dob" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" required />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Gender</label>
                                    <select name="gender" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Aadhar Card</label>
                                    <input type="text" name="aadhar_card" placeholder="12-digit Aadhar Number" maxlength="12" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Aadhar Card Type</label>
                                    <select name="aadhar_type" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2">
                                        <option value="">Select Type</option>
                                        <option value="Child">Child</option>
                                        <option value="Father">Father</option>
                                        <option value="Mother">Mother</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Parent Name</label>
                                    <input type="text" name="parent_name" placeholder="Parent/Guardian Name" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Contact Number</label>
                                    <input type="tel" name="contact_number" placeholder="Contact Number" maxlength="10" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" />
                                </div>
                                <div class="md:col-span-2 mt-4">
                                    <button type="submit" class="bg-govt-blue text-white px-4 py-2 rounded hover:bg-govt-dark-blue transition-colors">Register Child</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Face Registration Form -->
                    <div class="mb-6 border-govt-blue border-t-4 bg-white shadow-md rounded-lg">
                        <div class="p-4 border-b">
                            <h2 class="text-xl font-bold text-govt-blue">Face Registration</h2>
                        </div>
                        <div class="p-6">
                            <form id="faceRegistrationForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div id="webcamContainer" class="border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center p-8">
                                        <div class="text-center p-6">
                                            <div class="w-16 h-16 rounded-full bg-govt-light-blue mx-auto flex items-center justify-center mb-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-govt-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                            <p class="text-govt-blue font-medium">Camera feed will appear here</p>
                                            <p class="text-gray-500 text-sm mt-2">Make sure child's face is clearly visible</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-center gap-4">
                                        <button type="button" id="startWebcam" class="bg-govt-blue text-white px-4 py-2 rounded hover:bg-govt-dark-blue transition-colors">Start Camera</button>
                                        <button type="button" id="switchCamera" class="border-govt-blue text-govt-blue border px-4 py-2 rounded hover:bg-govt-light-blue transition-colors">Switch Camera</button>
                                        <button type="button" id="capturePhoto" class="border-govt-blue text-govt-blue border px-4 py-2 rounded hover:bg-govt-light-blue transition-colors">Take Photo</button>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Select Child</label>
                                        <select id="childSelect" name="child_id" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" required>
                                            <option value="">Select Child ID</option>
                                            <?php foreach ($children_without_face as $child): ?>
                                                <option value="<?= $child['child_id'] ?>"><?= $child['child_id'] ?> - <?= $child['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div id="capturedPhotoContainer" class="border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center p-8">
                                        <div class="text-center">
                                            <p class="text-govt-blue font-medium">Captured photo will appear here</p>
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full bg-govt-blue text-white px-4 py-2 rounded hover:bg-govt-dark-blue transition-colors">Register Face ID</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Children Records Table -->
                    <div class="bg-white shadow-md rounded-lg">
                        <div class="p-4 border-b">
                            <h2 class="text-xl font-bold">Children Records</h2>
                        </div>
                        <div class="p-6">
                            <div class="rounded-md border overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Child ID</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DOB</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aadhar</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aadhar Type</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="childrenTableBody">                                       
                                        <?php foreach ($children as $child): ?> 
                                            <tr>
                                                <td class='px-6 py-4 whitespace-nowrap font-medium'><?php echo htmlspecialchars($child['child_id'])?></td>
                                                <td class='px-6 py-4 whitespace-nowrap'><?php echo htmlspecialchars($child['name']) ?></td>
                                                <td class='px-6 py-4 whitespace-nowrap'><?php echo htmlspecialchars($child['dob']) ?></td>
                                                <td class='px-6 py-4 whitespace-nowrap'><?php echo htmlspecialchars($child['gender']) ?></td>
                                                <td class='px-6 py-4 whitespace-nowrap'><?php echo htmlspecialchars($child['parent_name']) ?></td>
                                                <td class='px-6 py-4 whitespace-nowrap'><?php echo htmlspecialchars($child['contact_number']) ?></td>
                                                <td class='px-6 py-4 whitespace-nowrap'><?php echo htmlspecialchars($child['aadhar_card']) ?></td>
                                                <td class='px-6 py-4 whitespace-nowrap'><?php echo htmlspecialchars($child['aadhar_type']) ?></td>
                                                <?php 
                                                    $imagePath = htmlspecialchars($child['image_path']); 
                                                    // Remove only the first occurrence of '../'
                                                    $correctedPath = preg_replace('/^\.\.\//', '', $imagePath, 1);
                                                ?>
                                                <td class='px-6 py-4 whitespace-nowrap'>
                                                    <?php if (!empty($imagePath)): ?>
                                                        <img src="<?php echo $correctedPath; ?>" alt="Child Image" class="w-12 h-12 object-cover rounded-md border">
                                                    <?php else: ?>
                                                        <span class="text-gray-400 text-sm">No Image</span>
                                                    <?php endif; ?>
                                                </td>

                                                <td class='px-6 py-4 whitespace-nowrap'>
                                                    <div class='flex gap-2'>
                                                        <button class='border-govt-blue text-govt-blue border px-3 py-1 text-sm rounded hover:bg-govt-light-blue transition-colors'>Edit</button>
                                                        <button class='border-red-500 text-red-500 border px-3 py-1 text-sm rounded hover:bg-red-50 transition-colors'>Delete</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>
        
                    <!-- Ration Distribution Tab -->
                    <input type="radio" name="tabs" id="tab-ration" class="tab-input">
                    <label for="tab-ration" class="tab-label text-lg py-3 w-1/3 text-center">Ration Distribution</label>
                    <div class="tab-content p-4">
                        <!-- ration distribution content will be added here -->
                        <div class="space-y-6">
    <!-- Distribute Ration Form -->
    <div class="mb-6 border-govt-blue border-t-4 bg-white shadow-md rounded-lg">
        <div class="p-4 border-b">
            <h2 class="text-xl font-bold text-govt-blue">Distribute Ration</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="?action=distribute_ration" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Select Child</label>
                    <select name="child_id" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" required>
                        <option value="">Select Child</option>
                        <?php foreach ($children as $child): ?>
                            <option value="<?= htmlspecialchars($child['child_id']) ?>">
                                <?= htmlspecialchars($child['child_id']) ?> - <?= htmlspecialchars($child['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Ration Type</label>
                    <select name="ration_type" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" required>
                        <option value="">Select Ration Type</option>
                        <option value="Chawal">Fortified Rice/Wheat Flour</option>
                        <option value="Dal">Pulses (Dal)</option>
                        <option value="Matar">Groundnuts/Peanuts</option>
                        <option value="Kichdi">Mixed Cereal-Pulse</option>
                        <option value="Sooji">Jaggery</option>
                        <option value="Horlicks">Fortified Drink Mix</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Quantity (kg/units)</label>
                    <input type="number" name="quantity" step="0.01" min="0.01" max="10" placeholder="e.g., 1.5" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" required />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Distribution Date</label>
                    <input type="date" name="distribution_date" value="<?= date('Y-m-d') ?>" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" required />
                </div>
                <div class="md:col-span-2 mt-4">
                    <button type="submit" class="bg-govt-blue text-white px-4 py-2 rounded hover:bg-govt-dark-blue transition-colors">Record Distribution</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Ration Distribution History -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-xl font-bold">Ration Distribution History</h2>
            <div class="flex items-center gap-2">
                <input type="date" id="filter-date" class="h-10 rounded-md border border-gray-300 px-3 py-2" value="<?= date('Y-m-d') ?>">
                <button id="filter-btn" class="bg-govt-blue text-white px-3 py-2 rounded hover:bg-govt-dark-blue transition-colors">Filter</button>
            </div>
        </div>
        <div class="p-6">
            <div class="rounded-md border overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Child ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ration Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="rationTableBody">
                        <!-- Ration records will be loaded here -->
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No records found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Tab -->

            <!-- Attendance Tab - Fixed: removed duplicate ID -->
            <input type="radio" name="tabs" id="tab-attendance-system" class="tab-input">
            <label for="tab-attendance-system" class="tab-label text-lg py-3 w-1/3 text-center">Attendance</label>
            <div class="tab-content p-4">
                <!-- Attendance Content -->
                <div class="space-y-6">
                    <!-- Face Recognition Attendance -->
                    <div class="mb-6 border-govt-blue border-t-4 bg-white shadow-md rounded-lg">
                        <div class="p-4 border-b">
                            <h2 class="text-xl font-bold text-govt-blue">Face Recognition Attendance</h2>
                        </div>
                        <div class="p-6">
                            <div id="attendance-video-container" class="relative w-full max-w-2xl mx-auto mb-4 border-2 border-dashed border-gray-300 rounded-lg">
                                <video id="attendance-video" width="640" height="480" autoplay class="w-full"></video>
                                <canvas id="attendance-overlay" class="absolute top-0 left-0 w-full h-full"></canvas>
                            </div>
                            <div class="flex flex-wrap justify-center gap-3">
                                <button id="attendance-start-button" class="bg-govt-blue text-white px-4 py-2 rounded hover:bg-govt-dark-blue transition-colors">
                                    Start Face Recognition
                                </button>
                                <button id="attendance-switch-button" class="border-govt-blue text-govt-blue border px-4 py-2 rounded hover:bg-govt-light-blue transition-colors">
                                    Switch Camera
                                </button>
                                <button id="attendance-end-button" class="border-govt-red text-govt-red border px-4 py-2 rounded hover:bg-red-50 transition-colors">
                                    End Attendance
                                </button>
                            </div>
                            <div id="attendance-message-div" class="mt-4 p-2 text-center hidden bg-green-100 text-green-800 rounded"></div>
                        </div>
                    </div>

                    <!-- Attendance Calendar -->
                    <div class="mb-6 border-govt-blue border-t-4 bg-white shadow-md rounded-lg">
                        <div class="p-4 border-b">
                            <h2 class="text-xl font-bold text-govt-blue">Attendance Reports</h2>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row gap-6">
                                <!-- Calendar -->
                                <div class="md:w-1/3">
                                    <div class="mb-4">
                                        <h3 class="text-lg font-medium mb-2">Select Date</h3>
                                        <input type="date" id="attendance-date-picker" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" value="<?= date('Y-m-d') ?>">
                                    </div>
                                    <button id="view-attendance-btn" class="w-full bg-govt-blue text-white px-4 py-2 rounded hover:bg-govt-dark-blue transition-colors">
                                        View Attendance
                                    </button>
                                </div>

                                <!-- Attendance Results -->
                                <div class="md:w-2/3">
                                    <div id="attendance-results" class="border rounded-lg">
                                        <div class="bg-gray-50 p-4 border-b">
                                            <h3 class="font-medium" id="attendance-date-display">Attendance for <?= date('d M Y') ?></h3>
                                        </div>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Child ID</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="attendance-records" class="bg-white divide-y divide-gray-200">
                                                    <tr>
                                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Select a date and click "View Attendance"</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
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
    // JavaScript for webcam and face registration
    const video = document.createElement('video');
    const webcamContainer = document.getElementById('webcamContainer');
    const capturedPhotoContainer = document.getElementById('capturedPhotoContainer');
    const startWebcamButton = document.getElementById('startWebcam');
    const switchCameraButton = document.getElementById('switchCamera');
    const capturePhotoButton = document.getElementById('capturePhoto');
    const faceRegistrationForm = document.getElementById('faceRegistrationForm');
    const childSelect = document.getElementById('childSelect');

    let videoStream;
    let currentDeviceIndex = 0;
    let videoDevices = [];

    // Get available camera devices
    async function getCameraDevices() {
        const devices = await navigator.mediaDevices.enumerateDevices();
        return devices.filter(device => device.kind === 'videoinput');
    }

    // Start webcam with specified device
    async function startWebcam(deviceId) {
        try {
            // Stop any existing stream
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
            }

            const constraints = {
                video: {
                    deviceId: deviceId ? { exact: deviceId } : undefined,
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                },
                audio: false
            };

            videoStream = await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject = videoStream;
            video.play();
            webcamContainer.innerHTML = '';
            webcamContainer.appendChild(video);
            
            // Update device list and show switch button if multiple cameras
            videoDevices = await getCameraDevices();
            if (videoDevices.length > 1) {
                switchCameraButton.classList.remove('hidden');
            } else {
                switchCameraButton.classList.add('hidden');
            }
        } catch (err) {
            console.error("Error accessing webcam:", err);
            alert("Failed to access webcam. Please ensure your camera is connected and permissions are granted.");
        }
    }

    // Start webcam with default camera
    startWebcamButton.addEventListener('click', async () => {
        videoDevices = await getCameraDevices();
        if (videoDevices.length > 0) {
            startWebcam(videoDevices[0].deviceId);
        } else {
            alert("No cameras found.");
        }
    });

    // Switch between available cameras
    switchCameraButton.addEventListener('click', async () => {
        currentDeviceIndex = (currentDeviceIndex + 1) % videoDevices.length;
        startWebcam(videoDevices[currentDeviceIndex].deviceId);
    });

    // Capture photo
    capturePhotoButton.addEventListener('click', () => {
        if (!videoStream) {
            alert("Please start the camera first.");
            return;
        }

        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Display captured photo
        const img = document.createElement('img');
        img.src = canvas.toDataURL('image/png');
        capturedPhotoContainer.innerHTML = '';
        capturedPhotoContainer.appendChild(img);

        // Store base64 image data in hidden input
        const imageDataInput = document.createElement('input');
        imageDataInput.type = 'hidden';
        imageDataInput.name = 'image_data';
        imageDataInput.value = canvas.toDataURL('image/png');
        faceRegistrationForm.appendChild(imageDataInput);
    });

    // Submit face registration form
    faceRegistrationForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!childSelect.value) {
            alert("Please select a child.");
            return;
        }

        const formData = new FormData(faceRegistrationForm);
        const response = await fetch('dashboard.php?action=register_face', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message);
            location.reload();
        } else {
            alert(result.message);
        }
    });

    // Clean up when leaving the page
    window.addEventListener('beforeunload', () => {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
        }
    });
    </script>
    
    <script>
    // --- Face Recognition Attendance System ---
    // Configuration
    const FACE_DETECTION_CONFIDENCE = 0.6;
    const FACE_MATCHER_THRESHOLD = 0.5;
    const COOLDOWN_TIME = 5000; // 5 seconds cooldown per child
    const FRAME_SKIP = 2; // Process every 2nd frame
    const lastMarked = {}; // Track last marked time for each child

    // DOM Elements
    const attendanceVideo = document.getElementById('attendance-video');
    const attendanceStartButton = document.getElementById('attendance-start-button');
    const attendanceSwitchButton = document.getElementById('attendance-switch-button');
    const attendanceEndButton = document.getElementById('attendance-end-button');
    const attendanceMessageDiv = document.getElementById('attendance-message-div');
    let attendanceVideoStream;
    let frameCount = 0;
    let currentAttendanceCamera = 0;

    // Load models when attendance tab is selected
    document.getElementById('tab-attendance-system').addEventListener('change', function() {
        if (this.checked) {
            loadFaceApiModels();
        } else {
            // Stop webcam if we switch away from attendance tab
            stopAttendanceWebcam();
        }
    });

    // Load face-api.js models
    async function loadFaceApiModels() {
        try {
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri('../assets/models'),
                faceapi.nets.faceLandmark68TinyNet.loadFromUri('../assets/models'),
                faceapi.nets.faceRecognitionNet.loadFromUri('../assets/models')
            ]);
            console.log("Face recognition models loaded");
        } catch (err) {
            console.error("Error loading models:", err);
            showAttendanceMessage("Failed to load face recognition models.", "error");
        }
    }

    // Start webcam with selected camera
    async function startAttendanceWebcam() {
        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(device => device.kind === "videoinput");

            if (videoDevices.length === 0) {
                showAttendanceMessage("No video devices found.", "error");
                return;
            }

            const deviceId = videoDevices[currentAttendanceCamera % videoDevices.length].deviceId;

            // Stop any previously running video stream
            if (attendanceVideoStream) {
                attendanceVideoStream.getTracks().forEach(track => track.stop());
            }

            // Start the selected camera
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    deviceId: { exact: deviceId },
                    width: 640,
                    height: 480
                },
                audio: false
            });

            attendanceVideo.srcObject = stream;
            attendanceVideoStream = stream;
            showAttendanceMessage("Camera started successfully", "success");
        } catch (err) {
            console.error("Error accessing webcam:", err);
            showAttendanceMessage("Failed to access webcam. Please ensure your camera is connected and permissions are granted.", "error");
        }
    }

    // Switch between available cameras
    function switchAttendanceCamera() {
        currentAttendanceCamera++;
        startAttendanceWebcam();
    }

    // Stop webcam
    function stopAttendanceWebcam() {
        if (attendanceVideoStream) {
            attendanceVideoStream.getTracks().forEach(track => track.stop());
            attendanceVideo.srcObject = null;
        }
    }

    // Get face descriptions for all children
    async function getChildrenFaceDescriptions() {
        const labeledFaceDescriptors = [];
        
        // Fetch children data from the database
        const response = await fetch('get_children_data.php');
        const children = await response.json();

        await Promise.all(children.map(async (child) => {
            const { child_id, image_path } = child;
            if (!image_path) return; // Skip if no image
            
            const descriptions = [];

            try {
                const img = await faceapi.fetchImage(image_path.replace('../', ''));
                const detections = await faceapi
                    .detectSingleFace(
                        img,
                        new faceapi.TinyFaceDetectorOptions({ inputSize: 160, scoreThreshold: FACE_DETECTION_CONFIDENCE })
                    )
                    .withFaceLandmarks(true)
                    .withFaceDescriptor();

                if (detections) {
                    descriptions.push(detections.descriptor);
                }
            } catch (err) {
                console.error(`Error processing ${image_path}:`, err);
            }

            if (descriptions.length > 0) {
                labeledFaceDescriptors.push(
                    new faceapi.LabeledFaceDescriptors(child_id, descriptions)
                );
            }
        }));

        return labeledFaceDescriptors;
    }

    // Start face recognition
    async function startFaceRecognition() {
        showAttendanceMessage("Loading face data...", "info");
        
        const labeledFaceDescriptors = await getChildrenFaceDescriptions();
        if (labeledFaceDescriptors.length === 0) {
            showAttendanceMessage("No face data available. Please register faces first.", "error");
            return;
        }
        
        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, FACE_MATCHER_THRESHOLD);
        const canvas = document.getElementById('attendance-overlay');
        const displaySize = { width: attendanceVideo.videoWidth, height: attendanceVideo.videoHeight };
        faceapi.matchDimensions(canvas, displaySize);
        
        showAttendanceMessage("Face recognition active - looking for children", "success");

        // Process video frames
        const recognitionInterval = setInterval(async () => {
            if (!attendanceVideoStream || attendanceVideoStream.active === false) {
                clearInterval(recognitionInterval);
                return;
            }

            frameCount++;
            if (frameCount % FRAME_SKIP !== 0) return; // Skip frames for performance

            const detections = await faceapi
                .detectAllFaces(
                    attendanceVideo,
                    new faceapi.TinyFaceDetectorOptions({ inputSize: 160, scoreThreshold: FACE_DETECTION_CONFIDENCE })
                )
                .withFaceLandmarks(true)
                .withFaceDescriptors();

            const resizedDetections = faceapi.resizeResults(detections, displaySize);
            const context = canvas.getContext('2d');
            context.clearRect(0, 0, canvas.width, canvas.height);

            const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));
            results.forEach((result, i) => {
                const box = resizedDetections[i].detection.box;
                if (result.label !== 'unknown' && result.distance < FACE_MATCHER_THRESHOLD) {
                    markAttendance(result.label);
                    context.strokeStyle = '#00FF00';
                } else {
                    context.strokeStyle = '#FF0000';
                }

                context.lineWidth = 2;
                context.strokeRect(box.x, box.y, box.width, box.height);
                context.fillStyle = context.strokeStyle;
                context.fillText(result.toString(), box.x + 6, box.y - 10);
            });
        }, 100);
        
        // Store the interval ID for cleanup
        window.recognitionInterval = recognitionInterval;
    }

    // Mark attendance for a child
   // Update the markAttendance function in your dashboard.php file
async function markAttendance(childId) {
    const now = Date.now();
    if (lastMarked[childId] && now - lastMarked[childId] < COOLDOWN_TIME) {
        return; // Don't mark too frequently
    }

    lastMarked[childId] = now;

    try {
        // Add console log to debug
        console.log(`Marking attendance for child: ${childId}`);
        
        const response = await fetch('dashboard.php?action=mark_attendance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `child_id=${encodeURIComponent(childId)}`
        });
        
        const result = await response.json();
        console.log("Attendance response:", result);
        
        if (result.status === 'success') {
            showAttendanceMessage(`Attendance marked for child ID: ${childId}`, "success");
        } else {
            showAttendanceMessage(result.message || "Error marking attendance", "error");
        }
    } catch (error) {
        console.error("Error marking attendance:", error);
        showAttendanceMessage("Failed to mark attendance", "error");
    }
}

    // Show messages
    function showAttendanceMessage(message, type = 'success') {
        attendanceMessageDiv.textContent = message;
        attendanceMessageDiv.classList.remove('hidden', 'bg-green-100', 'bg-red-100', 'bg-blue-100', 'text-green-800', 'text-red-800', 'text-blue-800');
        
        switch (type) {
            case 'error':
                attendanceMessageDiv.classList.add('bg-red-100', 'text-red-800');
                break;
            case 'info':
                attendanceMessageDiv.classList.add('bg-blue-100', 'text-blue-800');
                break;
            default:
                attendanceMessageDiv.classList.add('bg-green-100', 'text-green-800');
        }
        
        attendanceMessageDiv.classList.remove('hidden');
        
        // Clear the message after some time for success messages
        if (type === 'success') {
            setTimeout(() => {
                attendanceMessageDiv.classList.add('hidden');
            }, 3000);
        }
    }

    // Event listeners for attendance buttons
    attendanceStartButton.addEventListener('click', async () => {
        await startAttendanceWebcam();
        startFaceRecognition();
    });

    attendanceSwitchButton.addEventListener('click', switchAttendanceCamera);

    attendanceEndButton.addEventListener('click', () => {
        stopAttendanceWebcam();
        if (window.recognitionInterval) {
            clearInterval(window.recognitionInterval);
        }
        showAttendanceMessage('Attendance session ended.', 'info');
    });

    // --- Attendance Report Calendar ---
    document.getElementById('view-attendance-btn').addEventListener('click', async () => {
        const date = document.getElementById('attendance-date-picker').value;
        document.getElementById('attendance-date-display').textContent = `Attendance for ${new Date(date).toLocaleDateString('en-IN', {day: '2-digit', month: 'short', year: 'numeric'})}`;
        
        try {
            const formData = new FormData();
            formData.append('date', date);
            
            const response = await fetch('dashboard.php?action=attendance_reports', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            const tableBody = document.getElementById('attendance-records');
            
            if (data.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No attendance records found for this date</td></tr>`;
            } else {
                tableBody.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    const time = new Date(record.datetime).toLocaleTimeString('en-IN', {hour: '2-digit', minute:'2-digit'});
                    
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">${record.child_id}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${record.child_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${time}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                ${record.status}
                            </span>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            }
        } catch (error) {
            console.error("Error fetching attendance data:", error);
            document.getElementById('attendance-records').innerHTML = `
                <tr><td colspan="4" class="px-6 py-4 text-center text-red-500">Failed to load attendance data</td></tr>
            `;
        }
    });
    </script>
    <script>
     // Add this JavaScript at the end of your dashboard.php file
    document.addEventListener('DOMContentLoaded', function() {
    // If there's a hash in the URL and it's #tab-ration, trigger a click on the ration tab
    if (window.location.hash === '#tab-ration') {
        document.getElementById('tab-ration').checked = true;
        loadRationHistory();
    }
    
    // Add event listener for the filter button
    document.getElementById('filter-btn')?.addEventListener('click', loadRationHistory);
    
    // Load ration history initially when the tab is selected
    document.getElementById('tab-ration')?.addEventListener('change', function() {
        if (this.checked) {
            loadRationHistory();
        }
    });
    
    // Function to load ration history
    function loadRationHistory() {
        const date = document.getElementById('filter-date').value;
        const tableBody = document.getElementById('rationTableBody');
        
        tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center">Loading data...</td></tr>';
        
        const formData = new FormData();
        formData.append('date', date);
        
        fetch('dashboard.php?action=get_ration_history', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No records found</td></tr>';
                return;
            }
            
            tableBody.innerHTML = '';
            data.forEach(record => {
                // Format date for display
                const recordDate = new Date(record.datetime);
                const formattedDate = recordDate.toLocaleDateString('en-IN', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${record.child_id}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${record.child_name}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${record.ration_type}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${record.quantity} kg</td>
                    <td class="px-6 py-4 whitespace-nowrap">${formattedDate}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex gap-2">
                            <button data-id="${record.ration_id}" class="edit-ration border-govt-blue text-govt-blue border px-3 py-1 text-sm rounded hover:bg-govt-light-blue transition-colors">Edit</button>
                            <button data-id="${record.ration_id}" class="delete-ration border-red-500 text-red-500 border px-3 py-1 text-sm rounded hover:bg-red-50 transition-colors">Delete</button>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });
            
            // Add event listeners for edit and delete buttons
            document.querySelectorAll('.edit-ration').forEach(button => {
                button.addEventListener('click', () => {
                    // Handle edit functionality
                    alert('Edit functionality to be implemented for ration ID: ' + button.dataset.id);
                });
            });
            
            document.querySelectorAll('.delete-ration').forEach(button => {
                button.addEventListener('click', () => {
                    // Handle delete functionality
                    if (confirm('Are you sure you want to delete this ration distribution record?')) {
                        // Delete logic would go here
                        alert('Delete functionality to be implemented for ration ID: ' + button.dataset.id);
                    }
                });
            });
        })
        .catch(error => {
            console.error('Error loading ration data:', error);
            tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Failed to load data</td></tr>';
        });
    }
    
    // Show success/error messages
    const successMessage = "<?= $_SESSION['success_message'] ?? '' ?>";
    const errorMessage = "<?= $_SESSION['error_message'] ?? '' ?>";
    
    if (successMessage) {
        showNotification(successMessage, 'success');
        <?php unset($_SESSION['success_message']); ?>
    }
    
    if (errorMessage) {
        showNotification(errorMessage, 'error');
        <?php unset($_SESSION['error_message']); ?>
    }
    
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded shadow-lg ${
            type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
        }`;
        notification.textContent = message;
        
        // Add to document
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});   

    </script>
</body>
</html>