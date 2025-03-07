
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
            <div class="space-y-1 p-6">
                <h2 id="formTitle" class="text-2xl font-bold text-center text-govt-blue">
                    Forgot Password
                </h2>
                <p id="formDescription" class="text-center text-gray-500">
                    Enter your email or mobile number to receive a verification code
                </p>
            </div>
            
            <div class="p-6 pt-0">
                <!-- Step 1: Request OTP Form -->
                <form id="requestOtpForm" class="space-y-4">
                    <div class="space-y-2">
                        <label for="identifier" class="block text-sm font-medium text-gray-700">
                            Email or Mobile Number
                        </label>
                        <input
                            id="identifier"
                            type="text"
                            placeholder="Enter your registered email or mobile"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue"
                        />
                        <p id="identifierError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            id="requestOtpButton"
                            class="w-full h-10 bg-govt-blue hover:bg-govt-dark-blue text-white rounded-md transition-colors"
                        >
                            Send Verification Code
                        </button>
                    </div>
                </form>

                <!-- Step 2: Reset Password Form (Initially Hidden) -->
                <form id="resetPasswordForm" class="space-y-4 hidden">
                    <div class="space-y-2">
                        <label for="newPassword" class="block text-sm font-medium text-gray-700">
                            New Password
                        </label>
                        <input
                            id="newPassword"
                            type="password"
                            placeholder="Enter your new password"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue"
                        />
                        <p id="newPasswordError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="confirmPassword" class="block text-sm font-medium text-gray-700">
                            Confirm New Password
                        </label>
                        <input
                            id="confirmPassword"
                            type="password"
                            placeholder="Confirm your new password"
                            required
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue"
                        />
                        <p id="confirmPasswordError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            id="resetPasswordButton"
                            class="w-full h-10 bg-govt-blue hover:bg-govt-dark-blue text-white rounded-md transition-colors"
                        >
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="p-6 pt-0 flex justify-center">
                <div class="text-sm text-gray-600">
                    Remember your password?
                    <a href="login.php" class="text-govt-blue hover:underline font-medium">
                        Back to Login
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
        // Form handling
        document.getElementById('requestOtpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset errors
            const identifierError = document.getElementById('identifierError');
            identifierError.classList.add('hidden');
            
            const identifier = document.getElementById('identifier').value.trim();
            if (!identifier) {
                identifierError.innerText = 'Please enter your email or mobile number';
                identifierError.classList.remove('hidden');
                return;
            }
            
            const requestOtpButton = document.getElementById('requestOtpButton');
            requestOtpButton.innerText = 'Sending...';
            requestOtpButton.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                console.log('OTP request for:', identifier);
                
                // In a real app, this would send a request to the server
                // and redirect to the OTP verification page
                window.location.href = 'verify-otp.html';
                
                // Alternatively, if using page state to toggle between steps:
                // showResetPasswordForm();
            }, 1500);
        });
        
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset all errors
            document.querySelectorAll('[id$="Error"]').forEach(el => {
                el.classList.add('hidden');
            });
            
            let isValid = true;
            
            // Validate new password
            const newPassword = document.getElementById('newPassword').value;
            if (newPassword.length < 8) {
                showError('newPassword', 'Password must be at least 8 characters');
                isValid = false;
            }
            
            // Validate confirm password
            const confirmPassword = document.getElementById('confirmPassword').value;
            if (newPassword !== confirmPassword) {
                showError('confirmPassword', 'Passwords do not match');
                isValid = false;
            }
            
            if (isValid) {
                const resetPasswordButton = document.getElementById('resetPasswordButton');
                resetPasswordButton.innerText = 'Resetting...';
                resetPasswordButton.disabled = true;
                
                // Simulate API call
                setTimeout(() => {
                    console.log('Password reset completed');
                    
                    // Reset loading state
                    resetPasswordButton.innerText = 'Reset Password';
                    resetPasswordButton.disabled = false;
                    
                    // Show success message and redirect to login
                    alert('Password has been reset successfully!');
                    window.location.href = 'login.html';
                }, 1500);
            }
        });
        
        function showResetPasswordForm() {
            // Update title and description
            document.getElementById('formTitle').innerText = 'Reset Password';
            document.getElementById('formDescription').innerText = 'Create a new password for your account';
            
            // Hide request OTP form and show reset password form
            document.getElementById('requestOtpForm').classList.add('hidden');
            document.getElementById('resetPasswordForm').classList.remove('hidden');
        }
        
        function showError(field, message) {
            const errorElement = document.getElementById(`${field}Error`);
            errorElement.innerText = message;
            errorElement.classList.remove('hidden');
            
            // Add error styling to input
            document.getElementById(field).classList.add('border-red-500');
        }
        
        // Check URL parameters to see if we should show reset password form
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('step') === '2') {
            showResetPasswordForm();
        }
    </script>
</body>
</html>
