<?php
// cdo/reassign_supervisor.php

require '../includes/auth.php';
require '../includes/db.php';
require '../includes/functions.php';
redirectIfNotCDO();

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

// Fetch all supervisors under this CDO (excluding the one being deleted)
$stmt = $pdo->prepare("
    SELECT s.* 
    FROM supervisors s 
    WHERE s.cdo_id = ? AND s.supervisor_id != ?
");
$stmt->execute([$cdo_id, $supervisor_id]);
$supervisors = $stmt->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_supervisor_id = sanitizeInput($_POST['new_supervisor_id']);

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // Reassign Anganwadi Centres to the new supervisor
        $stmt = $pdo->prepare("UPDATE anganwadicentres SET supervisor_id = ? WHERE supervisor_id = ?");
        $stmt->execute([$new_supervisor_id, $supervisor_id]);

        // Delete the old supervisor
        $stmt = $pdo->prepare("DELETE FROM supervisors WHERE supervisor_id = ?");
        $stmt->execute([$supervisor_id]);

        // Delete the old supervisor's user record
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reassign Supervisor</title>
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
                        'govt-text-gray': '#4a5568'
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
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <!-- Header with Tricolor border -->
    <header class="bg-govt-blue shadow-md">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="../../client/index.php" class="flex items-center gap-2">
                    <img 
                        src="../assets/logo.png" 
                        alt="Logo" 
                        class="h-12"
                    />
                    <div class="text-white text-xl font-semibold">Pratham Path</div>
                </a>
            </div>
            <div class="tricolor-border mt-4"></div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="mt-10 mb-10 flex items-center justify-center bg-gray-50">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center text-govt-blue mb-6">Reassign Anganwadi Centres</h2>
            <p class="text-center text-gray-500 mb-6">
                The supervisor you are trying to delete is linked to one or more Anganwadi Centres. Please select a new supervisor to reassign the centres.
            </p>
            <form method="POST" class="space-y-4">
                <div class="space-y-2">
                    <label for="new_supervisor_id" class="block text-sm font-medium text-gray-700">Select New Supervisor</label>
                    <select
                        id="new_supervisor_id"
                        name="new_supervisor_id"
                        required
                        class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue"
                    >
                        <option value="" disabled selected>Select a supervisor</option>
                        <?php foreach ($supervisors as $supervisor): ?>
                            <option value="<?php echo $supervisor['supervisor_id']; ?>">
                                <?php echo htmlspecialchars($supervisor['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button
                    type="submit"
                    class="w-full h-10 bg-govt-blue hover:bg-govt-dark-blue text-white rounded-md transition-colors"
                >
                    Reassign and Delete
                </button>
            </form>
        </div>
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
</body>
</html>