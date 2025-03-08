<?php
session_start();
require 'includes/db.php'; // Database connection
require 'includes/functions.php'; // Database connection
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
        $user_id = null;
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
            // Generate a 6-digit OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;

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
                $mail->Subject = 'OTP for Password Reset';
                $mail->Body = "Your OTP for password reset is: <b>$otp</b>";

                $mail->send();
                $success = "OTP has been sent to your email.";
            } catch (Exception $e) {
                $error = "Failed to send OTP. Error: " . $mail->ErrorInfo;
            }
        } else {
            $error = "You are not registered in our portal.";
        }
    } elseif (isset($_POST['verify_otp'])) {
        // Step 2: Verify OTP
        $entered_otp = sanitizeInput($_POST['otp']);

        if ($entered_otp == $_SESSION['otp']) {
            // OTP verified, proceed to password reset
            $_SESSION['otp_verified'] = true;
            $success = "OTP verified. You can now reset your password.";
        } else {
            $error = "Invalid OTP. Please try again.";
        }
    } elseif (isset($_POST['reset_password'])) {
        // Step 3: Reset Password
        if (isset($_SESSION['otp_verified']) && $_SESSION['otp_verified']) {
            $new_password = sanitizeInput($_POST['new_password']);
            $confirm_password = sanitizeInput($_POST['confirm_password']);

            if ($new_password === $confirm_password) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update password in the users table
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                $stmt->execute([$hashed_password, $_SESSION['user_id']]);

                // Clear session data
                unset($_SESSION['otp']);
                unset($_SESSION['otp_verified']);
                unset($_SESSION['email']);
                unset($_SESSION['user_id']);
                unset($_SESSION['role']);

                $success = "Password reset successfully. Redirecting to Login Page...";
            } else {
                $error = "Passwords do not match.";
            }
            if($success){
                echo '<script>
                setTimeout(function() {
                    window.location.href = "login.php";
                }, 2000);
                </script>';
            }
        } else {
            $error = "OTP verification required.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Pratham Path</title>
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
    <main class="flex-grow flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md border-t-4 border-t-govt-blue shadow-lg bg-white rounded-lg">
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
                <div class="space-y-1 p-6">
                    <h2 class="text-2xl font-bold text-center text-govt-blue">Forgot Password</h2>
                    <p class="text-center text-gray-500">
                        Enter your registered email to reset your password
                    </p>
                </div>
                <div class="p-6 pt-0">
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
                </div>
            <?php elseif (!isset($_SESSION['otp_verified'])): ?>
                <!-- Step 2: Verify OTP -->
                <div class="space-y-1 p-6">
                    <h2 class="text-2xl font-bold text-center text-govt-blue">
                        Verify OTP
                    </h2>
                    <p class="text-center text-gray-500" id="otpDescription">
                        Enter the 6-digit code sent to your email/mobile
                    </p>
                </div>
                <div class="p-6 pt-0">
                    <form method="POST" class="space-y-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-center space-x-2">
                                <input type="text" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" maxlength="1" id="otp-0" autofocus>
                                <input type="text" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" maxlength="1" id="otp-1">
                                <input type="text" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" maxlength="1" id="otp-2">
                                <input type="text" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" maxlength="1" id="otp-3">
                                <input type="text" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" maxlength="1" id="otp-4">
                                <input type="text" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" maxlength="1" id="otp-5">
                            </div>
                            <input type="hidden" name="otp" id="otpInput">
                            <p id="otpError" class="text-red-500 text-xs text-center mt-2 hidden"></p>
                            <div class="text-center text-sm text-gray-600">
                                <p id="resendTimer">Resend code in <span id="countdown">30</span> seconds</p>
                                <button type="button" id="resendOtpButton" class="text-govt-blue hover:underline focus:outline-none hidden">
                                    Resend verification code
                                </button>
                            </div>
                        </div>
                        <button
                            type="submit"
                            name="verify_otp"
                            id="verifyButton"
                            class="w-full h-10 bg-govt-blue hover:bg-govt-dark-blue text-white rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            Verify OTP
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <!-- Step 3: Reset Password -->
                <div class="space-y-1 p-6">
                    <h2 class="text-2xl font-bold text-center text-govt-blue">Reset Password</h2>
                    <p class="text-center text-gray-500">
                        Enter your new password
                    </p>
                </div>
                <div class="p-6 pt-0">
                    <form method="POST" class="space-y-4">
                        <div class="space-y-2">
                            <label for="new_password" class="block text-sm font-medium text-gray-700">
                                New Password
                            </label>
                            <input
                                type="password"
                                id="new_password"
                                name="new_password"
                                required
                                class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue"
                            />
                        </div>
                        <div class="space-y-2">
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700">
                                Confirm Password
                            </label>
                            <input
                                type="password"
                                id="confirm_password"
                                name="confirm_password"
                                required
                                class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue"
                            />
                        </div>
                        <button
                            type="submit"
                            name="reset_password"
                            class="w-full h-10 bg-govt-blue hover:bg-govt-dark-blue text-white rounded-md transition-colors"
                        >
                            Reset Password
                        </button>
                    </form>
                </div>
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

    <script>
        // OTP Input Handling
        const otpInputs = document.querySelectorAll('input[type="text"][maxlength="1"]');
        const otpHiddenInput = document.getElementById('otpInput');
        const verifyButton = document.getElementById('verifyButton');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                updateOTP();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && index > 0 && !e.target.value) {
                    otpInputs[index - 1].focus();
                }
                updateOTP();
            });
        });

        function updateOTP() {
            const otp = Array.from(otpInputs).map(input => input.value).join('');
            otpHiddenInput.value = otp;
            verifyButton.disabled = otp.length !== 6;
        }

        // Resend OTP Timer
        let timeLeft = 30;
        const resendTimer = document.getElementById('resendTimer');
        const resendButton = document.getElementById('resendOtpButton');

        const timer = setInterval(() => {
            timeLeft--;
            document.getElementById('countdown').textContent = timeLeft;

            if (timeLeft <= 0) {
                clearInterval(timer);
                resendTimer.classList.add('hidden');
                resendButton.classList.remove('hidden');
            }
        }, 1000);

        resendButton.addEventListener('click', () => {
            // Resend OTP logic here
            alert('Resend OTP functionality to be implemented.');
        });
    </script>
</body>
</html>