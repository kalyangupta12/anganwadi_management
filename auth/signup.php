<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Pratham Path</title>
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
                        class="h-12" />
                    <div class="text-white text-xl font-semibold">Pratham Path</div>
                </a>
            </div>
            <div class="tricolor-border mt-4"></div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-8 px-4">
        <div class="w-full max-w-md border-t-4 border-t-govt-blue shadow-lg bg-white rounded-lg my-8">
            <div class="space-y-1 p-6">
                <h2 class="text-2xl font-bold text-center text-govt-blue">Create an Account</h2>
                <p class="text-center text-gray-500">
                    Register to access Anganwadi services
                </p>
            </div>

            <div class="p-6 pt-0">
                <form id="signupForm" class="space-y-4">
                    <!-- Role Selection Dropdown -->
                    <div class="space-y-2">
                        <label for="role" class="block text-sm font-medium text-gray-700">
                            Select Role
                        </label>
                        <select
                            id="role"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue">
                            <option value="" disabled selected>Select your role</option>
                            <option value="cdo">CDO</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="worker">Worker</option>
                        </select>
                        <p id="roleError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Full Name
                        </label>
                        <input
                            id="name"
                            type="text"
                            placeholder="Enter your full name"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" />
                        <p id="nameError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email Address
                        </label>
                        <input
                            id="email"
                            type="email"
                            placeholder="Enter your email address"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" />
                        <p id="emailError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <div class="space-y-2">
                        <label for="mobile" class="block text-sm font-medium text-gray-700">
                            Mobile Number
                        </label>
                        <input
                            id="mobile"
                            type="tel"
                            placeholder="Enter your 10-digit mobile number"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" />
                        <p id="mobileError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <div class="space-y-2">
                        <label for="aadharNumber" class="block text-sm font-medium text-gray-700">
                            Aadhar Number
                        </label>
                        <input
                            id="aadharNumber"
                            type="text"
                            placeholder="Enter your 12-digit Aadhar number"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" />
                        <p id="aadharError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <input
                            id="password"
                            type="password"
                            placeholder="Create a password (min. 8 characters)"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" />
                        <p id="passwordError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <div class="space-y-2">
                        <label for="confirmPassword" class="block text-sm font-medium text-gray-700">
                            Confirm Password
                        </label>
                        <input
                            id="confirmPassword"
                            type="password"
                            placeholder="Confirm your password"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" />
                        <p id="confirmPasswordError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            id="registerButton"
                            class="w-full h-10 bg-govt-blue hover:bg-govt-dark-blue text-white rounded-md transition-colors">
                            Register
                        </button>
                    </div>
                </form>
            </div>

            <div class="p-6 pt-0 flex justify-center">
                <div class="text-sm text-gray-600">
                    Already have an account?
                    <a href="login.php" class="text-govt-blue hover:underline font-medium">
                        Login here
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer with Tricolor border -->
    <footer class="pt-16 pb-8">
        <div class="container mx-auto px-4">
            <!-- National emblem and copyright -->
            <div
                class="pt-8 border-t border-gray-200 flex flex-col items-center justify-center text-center">
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
        // Form validation
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Reset all errors
            document.querySelectorAll('[id$="Error"]').forEach(el => {
                el.classList.add('hidden');
            });

            let isValid = true;

            // Validate name
            const name = document.getElementById('name').value.trim();
            if (!name) {
                showError('name', 'Name is required');
                isValid = false;
            }

            // Validate email
            const email = document.getElementById('email').value.trim();
            if (!email) {
                showError('email', 'Email is required');
                isValid = false;
            } else if (!/\S+@\S+\.\S+/.test(email)) {
                showError('email', 'Email is invalid');
                isValid = false;
            }

            // Validate mobile
            const mobile = document.getElementById('mobile').value.trim();
            if (!mobile) {
                showError('mobile', 'Mobile number is required');
                isValid = false;
            } else if (!/^\d{10}$/.test(mobile)) {
                showError('mobile', 'Mobile number must be 10 digits');
                isValid = false;
            }

            // Validate Aadhar
            const aadhar = document.getElementById('aadharNumber').value.trim();
            if (!aadhar) {
                showError('aadhar', 'Aadhar number is required');
                isValid = false;
            } else if (!/^\d{12}$/.test(aadhar)) {
                showError('aadhar', 'Aadhar number must be 12 digits');
                isValid = false;
            }

            // Validate password
            const password = document.getElementById('password').value;
            if (!password) {
                showError('password', 'Password is required');
                isValid = false;
            } else if (password.length < 8) {
                showError('password', 'Password must be at least 8 characters');
                isValid = false;
            }

            // Validate confirm password
            const confirmPassword = document.getElementById('confirmPassword').value;
            if (password !== confirmPassword) {
                showError('confirmPassword', 'Passwords do not match');
                isValid = false;
            }

            if (isValid) {
                const registerButton = document.getElementById('registerButton');
                registerButton.innerText = 'Registering...';
                registerButton.disabled = true;

                // Simulate API call
                setTimeout(() => {
                    console.log('Registration attempt with:', {
                        name,
                        email,
                        mobile,
                        aadhar,
                        password
                    });

                    // Reset loading state
                    registerButton.innerText = 'Register';
                    registerButton.disabled = false;

                    // Redirect to OTP verification page
                    window.location.href = 'verify-otp.html';
                }, 1500);
            }
        });

        function showError(field, message) {
            const errorElement = document.getElementById(`${field}Error`);
            errorElement.innerText = message;
            errorElement.classList.remove('hidden');

            // Add error styling to input
            document.getElementById(field).classList.add('border-red-500');
        }
    </script>
</body>

</html>