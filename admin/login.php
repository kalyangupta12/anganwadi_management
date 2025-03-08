<?php
// PHP Backend Logic
session_start();
require 'includes/db.php';
require 'includes/auth.php';
require 'includes/functions.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);

    // Fetch user from centralized users table
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && verifyPassword($password, $user['password'])) {
        // Set session based on role
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        switch ($user['role']) {
            case 'CDO':
                header('Location: cdo/dashboard.php');
                break;
            case 'Supervisor':
                header('Location: supervisor/dashboard.php');
                break;
            case 'Worker':
                header('Location: worker/dashboard.php');
                break;
        }
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pratham Path</title>
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
            <div class="space-y-1 p-6">
                <h2 class="text-2xl font-bold text-center text-govt-blue">Login</h2>
                <p class="text-center text-gray-500">
                    Enter your credentials to access your account
                </p>
            </div>
            
            <div class="p-6 pt-0">
                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <form id="loginForm" method="POST" class="space-y-4">
                    <!-- Role Selection Dropdown -->
                    <!-- <div class="space-y-2">
                        <label for="role" class="block text-sm font-medium text-gray-700">
                            Select Role
                        </label>
                        <select
                            id="role"
                            name="role"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue"
                        >
                            <option value="" disabled selected>Select your role</option>
                            <option value="cdo">CDO</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="worker">Worker</option>
                        </select>
                    </div> -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Username
                        </label>
                        <input
                            id="email"
                            name="username"
                            type="text"
                            placeholder="Enter your username"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue"
                        />
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Password
                            </label>
                            <a href="forgot_password.php" class="text-sm text-govt-blue hover:underline">
                                Forgot password?
                            </a>
                        </div>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Enter your password"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue"
                        />
                    </div>
                    
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            id="loginButton"
                            class="w-full h-10 bg-govt-blue hover:bg-govt-dark-blue text-white rounded-md transition-colors"
                        >
                            Login
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="p-6 pt-0 flex flex-col space-y-4 text-center">
                <div class="text-sm text-gray-600">
                    Forgot your username?
                    <a href="retrieve_username.php" class="text-govt-blue hover:underline font-medium">
                        Retrieve it here
                    </a>
                </div>
            </div>
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
        // Simple form handling
        // document.getElementById('loginForm').addEventListener('submit', function(e) {
        //     const loginButton = document.getElementById('loginButton');
            
        //     // Show loading state
        //     loginButton.innerText = 'Logging in...';
        //     loginButton.disabled = true;
        // });
    </script>
</body>
</html>