<?php
error_reporting(0); // Hides all errors and warnings
ini_set('display_errors', 0);

session_start();

require 'includes/db.php'; 
require 'includes/functions.php';// Database connection
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_otp'])) {
        // Step 1: Send OTP to the provided email
        $email = sanitizeInput($_POST['email']);

        // Check if the email exists in any of the tables (cdo, supervisors, workers)
        $tables = ['cdo', 'supervisors', 'workers'];
        $user = null;
        $role = '';

        foreach ($tables as $table) {
            $stmt = $pdo->prepare("SELECT user_id FROM $table WHERE email = ?");
            $stmt->execute([$email]);
            $result = $stmt->fetch();

            if ($result) {
                $user_id = $result['user_id'];
                $role = $table; // Store the role (cdo, supervisors, workers)
                break;
            }
        }

        if ($user_id) {
            // Fetch username from the users table using user_id
            $stmt = $pdo->prepare("SELECT username FROM users WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();

            if ($user) {
                // Generate a 6-digit OTP
                $otp = rand(100000, 999999);
                $_SESSION['otp'] = $otp;
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $user['username'];

                // Send OTP via email
                $mail = new PHPMailer(true);

                try {
                    // SMTP configuration
                    $mail->isSMTP();
                    $mail->Host = 'smtp.hostinger.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'prathampath@ccsdu.in';
                    $mail->Password = '786004@Prathampath';
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('prathampath@ccsdu.in', 'Pratham Path');
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'OTP for Username Retrieval';
                    $mail->Body = "Your OTP for username retrieval is: <b>$otp</b>";

                    $mail->send();
                    $success = "OTP has been sent to your email.";
                } catch (Exception $e) {
                    $error = "Failed to send OTP. Error: " . $mail->ErrorInfo;
                }
            } else {
                $error = "User not found in the users table.";
            }
        } else {
            $error = "Email not found! You are not registered.";
        }
    } elseif (isset($_POST['verify_otp'])) {
        // Step 2: Verify OTP
        $entered_otp = sanitizeInput($_POST['otp']);

        if ($entered_otp == $_SESSION['otp']) {
            // OTP verified, display the username
            $success = "Your username is: <b>" . $_SESSION['username'] . "</b>";
            unset($_SESSION['otp']); // Clear OTP from session
        } else {
            $error = "Invalid OTP. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrieve Username - Pratham Path</title>
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
                <a href="../client/index.php" class="flex items-center gap-2">
                    <img 
                        src="../client/assets/logo.png" 
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
            <h2 class="text-2xl font-bold text-center text-govt-blue mb-6">Retrieve Username</h2>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($_SESSION['otp'])): ?>
                <!-- Step 1: Enter Email to Send OTP -->
                <form method="POST" class="space-y-4">
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Enter your registered email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue"
                        />
                    </div>
                    <button
                        type="submit"
                        name="send_otp"
                        class="w-full h-10 bg-govt-blue hover:bg-govt-dark-blue text-white rounded-md transition-colors"
                    >
                        Send OTP
                    </button>
                </form>
            <?php else: ?>
                <!-- Step 2: Verify OTP -->
                <form method="POST" class="space-y-4">
                    <div class="space-y-2">
                        <label for="otp" class="block text-sm font-medium text-gray-700">
                            Enter OTP sent to your email
                        </label>
                        <input
                            type="text"
                            id="otp"
                            name="otp"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue"
                        />
                    </div>
                    <button
                        type="submit"
                        name="verify_otp"
                        class="w-full h-10 bg-govt-blue hover:bg-govt-dark-blue text-white rounded-md transition-colors"
                    >
                        Verify OTP
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer with Tricolor border -->
    <footer class="pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="pt-8 border-t border-gray-200 flex flex-col items-center justify-center text-center">
                <div class="mb-4 flex flex-col items-center">
                    <div class="w-16 h-full mb-2">
                        <img
                            src="../client/assets/image.png"
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