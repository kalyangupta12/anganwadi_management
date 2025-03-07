<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Pratham Path</title>
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
    <main class="flex-grow flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md border-t-4 border-t-govt-blue shadow-lg bg-white rounded-lg">
            <div class="space-y-1 p-6">
                <h2 class="text-2xl font-bold text-center text-govt-blue">
                    Verify OTP
                </h2>
                <p class="text-center text-gray-500" id="otpDescription">
                    Enter the 6-digit code sent to your email/mobile
                </p>
            </div>

            <div class="p-6 pt-0">
                <form id="verifyOtpForm" class="space-y-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-center space-x-2">
                            <input type="text" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" maxlength="1" id="otp-0" autofocus>
                            <input type="text" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" maxlength="1" id="otp-1">
                            <input type="text" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" maxlength="1" id="otp-2">
                            <input type="text" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" maxlength="1" id="otp-3">
                            <input type="text" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" maxlength="1" id="otp-4">
                            <input type="text" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" maxlength="1" id="otp-5">
                        </div>

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
                        id="verifyButton"
                        class="w-full h-10 bg-govt-blue hover:bg-govt-dark-blue text-white rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        Verify OTP
                    </button>
                </form>
            </div>

            <div class="p-6 pt-0 flex justify-center">
                <div class="text-sm text-gray-600">
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
        // Handle OTP input
        const otpInputs = document.querySelectorAll('[id^="otp-"]');
        const verifyButton = document.getElementById('verifyButton');

        // Set up OTP input focus behavior
        otpInputs.forEach((input, index) => {
            // Allow only numbers
            input.addEventListener('input', function(e) {
                const value = e.target.value;
                if (value && !/^\d+$/.test(value)) {
                    e.target.value = '';
                    return;
                }

                // Move to next input if value is entered
                if (value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }

                // Enable verify button if all inputs have values
                updateVerifyButton();
            });

            // Handle backspace to go to previous input
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        // Handle paste functionality
        document.getElementById('otp-0').addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').trim();

            // If pasted data is a 6-digit number
            if (/^\d{6}$/.test(pastedData)) {
                const digits = pastedData.split('');
                digits.forEach((digit, i) => {
                    if (i < otpInputs.length) {
                        otpInputs[i].value = digit;
                    }
                });

                // Focus the last input
                otpInputs[otpInputs.length - 1].focus();
                updateVerifyButton();
            }
        });

        // Update verify button state
        function updateVerifyButton() {
            let allFilled = true;

            otpInputs.forEach(input => {
                if (!input.value) {
                    allFilled = false;
                }
            });

            verifyButton.disabled = !allFilled;
        }

        // Resend OTP timer
        let timeLeft = 30;
        const countdownElement = document.getElementById('countdown');
        const resendTimer = document.getElementById('resendTimer');
        const resendOtpButton = document.getElementById('resendOtpButton');

        const timer = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(timer);
                resendTimer.classList.add('hidden');
                resendOtpButton.classList.remove('hidden');
                return;
            }

            timeLeft--;
            countdownElement.textContent = timeLeft;
        }, 1000);

        // Resend OTP handler
        resendOtpButton.addEventListener('click', function() {
            // Reset timer
            timeLeft = 30;
            countdownElement.textContent = timeLeft;
            resendTimer.classList.remove('hidden');
            resendOtpButton.classList.add('hidden');

            // Restart timer
            const newTimer = setInterval(() => {
                if (timeLeft <= 0) {
                    clearInterval(newTimer);
                    resendTimer.classList.add('hidden');
                    resendOtpButton.classList.remove('hidden');
                    return;
                }

                timeLeft--;
                countdownElement.textContent = timeLeft;
            }, 1000);

            // Simulate API call for resending OTP
            console.log('Resending OTP');
        });

        // Form submission
        document.getElementById('verifyOtpForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get OTP value
            let otpValue = '';
            otpInputs.forEach(input => {
                otpValue += input.value;
            });

            // Validate OTP length
            if (otpValue.length !== 6) {
                showError('Please enter the complete 6-digit verification code');
                return;
            }

            // Reset error if any
            document.getElementById('otpError').classList.add('hidden');

            // Show loading state
            verifyButton.innerText = 'Verifying...';
            verifyButton.disabled = true;

            // Simulate API call
            setTimeout(() => {
                console.log('Verifying OTP:', otpValue);

                // Determine the redirect based on URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                const from = urlParams.get('from') || 'signup';

                if (from === 'forgot-password') {
                    // Redirect to reset password step
                    window.location.href = 'forgot-password.html?step=2';
                } else {
                    // Assume signup flow, redirect to login with success message
                    alert('Account verified successfully! You can now login.');
                    window.location.href = 'login.html';
                }
            }, 1500);
        });

        function showError(message) {
            const errorElement = document.getElementById('otpError');
            errorElement.innerText = message;
            errorElement.classList.remove('hidden');
        }

        // Update description with identifier from URL if available
        const urlParams = new URLSearchParams(window.location.search);
        const identifier = urlParams.get('identifier');
        if (identifier) {
            document.getElementById('otpDescription').innerText = `Enter the 6-digit code sent to ${identifier}`;
        }
    </script>
</body>

</html>