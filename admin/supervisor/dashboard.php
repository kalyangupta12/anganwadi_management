<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/functions.php';
redirectIfNotSupervisor();

$action = isset($_GET['action']) ? $_GET['action'] : 'view';

$validActions = ['add_workers', 'edit_workers', 'delete_workers', 'view_workers', 'add_centres', 'edit_centres', 'delete_centres', 'view_centres', 'view_notifications', 'raise_issue', 'view_issues', 'log_visits', 'view_visits'];

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
                    $stmt->execute([$user_id, $centre_id, $name, $gender, $aadhar, $emergency_contact, $contact_number, $email,$id_type, $id_path, $qualification_type, $qualification_path]);
            
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
            $stmt->execute([$name, $gender, $aadhar,$id_type, $id_path, $qualification_type, $qualification_path, $emergency_contact, $contact_number, $email, $centre_id, $worker_id]);
        
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
function uploadFile($file, $uploadDir) {
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
    <title>Supervisor Dashboard</title>
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
            <div class="hidden md:flex gap-6">
                <a href="workers/view.php" class="text-white text-lg hover:text-govt-light-blue">Workers</a>
                <a href="centres/view.php" class="text-white text-lg hover:text-govt-light-blue">Centres</a>
                <a href="../logout.php" class="font-bold text-white text-xl hover:text-govt-light-blue">Logout</a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="menu-toggle" class="md:hidden text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
        
        <!-- Mobile Navigation (Hidden by default) -->
        <!-- <div id="mobile-menu" class="hidden md:hidden bg-govt-blue mt-4 rounded-lg shadow-lg">
            <div class="px-4 py-4">
                <a href="workers/view.php" class="block text-white text-lg hover:text-govt-light-blue mb-2">Workers</a>
                <a href="centres/view.php" class="block text-white text-lg hover:text-govt-light-blue mb-2">Centres</a>
                <a href="../logout.php" class="block font-bold text-white text-xl hover:text-govt-light-blue">Logout</a>
            </div>
        </div> -->

        <div class="tricolor-border mt-4"></div>
    </div>
</header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
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
            <div id="notification-card" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer border-l-4 border-govt-red">
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

                <!-- Buttons (Hidden by Default) -->
                <div id="notification-buttons" class="hidden flex flex-col gap-4 mt-4">
                    <a href="?action=add_notification" class="bg-blue-500 text-white px-4 py-2 rounded-md text-center hover:bg-blue-600">Add</a>
                    <a href="?action=edit_notification" class="bg-yellow-500 text-white px-4 py-2 rounded-md text-center hover:bg-yellow-600">Edit</a>
                    <a href="?action=view_notifications" class="bg-green-500 text-white px-4 py-2 rounded-md text-center hover:bg-green-600">View</a>
                    <a href="?action=delete_notification" class="bg-red-500 text-white px-4 py-2 rounded-md text-center hover:bg-red-600">Delete</a>
                </div>
            </div>
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
                    <p class="text-sm text-gray-500 mt-2">Current File:  <a href="<?php echo htmlspecialchars('/anganwadi_management/admin' . $workers['qualification_path']); ?>" target="_blank" class="text-blue-500 hover:underline">View File</a></p>
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
        <a href="?action=add_centres" class="bg-govt-blue text-white px-4 py-2 rounded-md text-center hover:bg-blue-600 mb-4 "> + Add Anganwadi Centres</a>           
    </div>

    
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