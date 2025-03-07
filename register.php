<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Supervisor Registration Form</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'govt-blue': '#0066cc',
            'govt-dark-blue': '#003366',
            'govt-light-blue': '#e6f0f9',
            'govt-orange': '#ff9933',
            'govt-text-gray': '#4b5563',
            'govt-green': '#138808',
            'govt-light-green': '#e6f7e6'
          }
        }
      }
    }
  </script>
</head>
<body class="bg-gray-50">
  <!-- Header with national emblem -->
  <?php include 'includes/header.php'; ?>
  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
      <!-- Page Title and Information -->
      <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-govt-dark-blue mb-2">Supervisor Registration</h1>
        <p class="text-govt-text-gray">Complete the form below to register as a supervisor. Your application will be reviewed by the Chief Development Officer (CDO) before activation.</p>
      </div>

      <!-- Registration Steps -->
      <div class="mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
          <div class="flex items-center mb-4 md:mb-0">
            <div class="bg-govt-blue text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">1</div>
            <div class="ml-3">
              <p class="font-medium text-govt-dark-blue">Submit Application</p>
              <p class="text-sm text-govt-text-gray">Fill and submit registration form</p>
            </div>
          </div>
          <div class="w-0.5 h-8 bg-gray-300 hidden md:block"></div>
          <div class="flex items-center mb-4 md:mb-0">
            <div class="bg-gray-300 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">2</div>
            <div class="ml-3">
              <p class="font-medium text-govt-text-gray">CDO Verification</p>
              <p class="text-sm text-govt-text-gray">CDO reviews your application</p>
            </div>
          </div>
          <div class="w-0.5 h-8 bg-gray-300 hidden md:block"></div>
          <div class="flex items-center">
            <div class="bg-gray-300 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">3</div>
            <div class="ml-3">
              <p class="font-medium text-govt-text-gray">Account Activation</p>
              <p class="text-sm text-govt-text-gray">Start using your account</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Registration Form -->
      <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <form class="space-y-6">
          <!-- Personal Information -->
          <div>
            <h3 class="text-lg font-bold text-govt-dark-blue mb-4 pb-2 border-b border-gray-200">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue">
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Date of Birth <span class="text-red-500">*</span></label>
                <input type="date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue">
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Gender <span class="text-red-500">*</span></label>
                <select required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue">
                  <option value="">Select Gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Aadhaar Number <span class="text-red-500">*</span></label>
                <input type="text" required pattern="[0-9]{12}" placeholder="12 digit Aadhaar number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue">
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <div>
            <h3 class="text-lg font-bold text-govt-dark-blue mb-4 pb-2 border-b border-gray-200">Contact Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Mobile Number <span class="text-red-500">*</span></label>
                <input type="tel" required pattern="[0-9]{10}" placeholder="10 digit mobile number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue">
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Email Address <span class="text-red-500">*</span></label>
                <input type="email" id="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue">
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Residential Address <span class="text-red-500">*</span></label>
                <textarea rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue"></textarea>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">District <span class="text-red-500">*</span></label>
                <select required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue">
                  <option value="">Select District</option>
                  <option value="kamrup">Kamrup Metropolitan</option>
                  <option value="dibrugarh">Dibrugarh</option>
                  <option value="jorhat">Jorhat</option>
                  <option value="sivasagar">Sivasagar</option>
                  <option value="tinsukia">Tinsukia</option>
                  <!-- More districts can be added here -->
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">PIN Code <span class="text-red-500">*</span></label>
                <input type="text" required pattern="[0-9]{6}" placeholder="6 digit PIN code" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue">
              </div>
            </div>
          </div>

          <!-- Professional Information -->
          <div>
            <h3 class="text-lg font-bold text-govt-dark-blue mb-4 pb-2 border-b border-gray-200">Professional Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Highest Qualification <span class="text-red-500">*</span></label>
                <select required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue">
                  <option value="">Select Qualification</option>
                  <option value="high_school">High School</option>
                  <option value="higher_secondary">Higher Secondary</option>
                  <option value="graduate">Graduate</option>
                  <option value="post_graduate">Post Graduate</option>
                  <option value="phd">Ph.D</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Years of Experience <span class="text-red-500">*</span></label>
                <select required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue">
                  <option value="">Select Experience</option>
                  <option value="0-2">0-2 years</option>
                  <option value="3-5">3-5 years</option>
                  <option value="6-10">6-10 years</option>
                  <option value="10+">More than 10 years</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Document Upload -->
          <div>
            <h3 class="text-lg font-bold text-govt-dark-blue mb-4 pb-2 border-b border-gray-200">Document Upload</h3>
            <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Photo ID (Aadhaar/Voter ID/PAN) <span class="text-red-500">*</span></label>
                <label class="flex flex-col rounded-lg border-2 border-dashed w-full h-32 p-10 group text-center border-gray-300 hover:border-govt-blue cursor-pointer">
                    <div class="h-full w-full text-center flex flex-col items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-govt-blue">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    <p class="text-sm text-gray-400 group-hover:text-govt-blue pt-1">Select a file or drag and drop</p>
                    <p class="text-xs text-gray-400 group-hover:text-govt-blue">(JPG, PNG or PDF, max 2MB)</p>
                    </div>
                    <input type="file" id="photo-id" class="hidden" accept=".jpg,.jpeg,.png,.pdf">
                </label>
                <p id="file-name" class="text-sm text-gray-600 mt-2"></p> <!-- File name display -->
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Highest Qualification Certificate <span class="text-red-500">*</span></label>
                <div class="flex items-center justify-center w-full">
                  <label class="flex flex-col rounded-lg border-2 border-dashed w-full h-32 p-10 group text-center border-gray-300 hover:border-govt-blue">
                    <div class="h-full w-full text-center flex flex-col items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-govt-blue">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                      </svg>
                      <p class="text-sm text-gray-400 group-hover:text-govt-blue pt-1">Select a file or drag and drop</p>
                      <p class="text-xs text-gray-400 group-hover:text-govt-blue">(JPG, PNG or PDF, max 2MB)</p>
                    </div>
                    <input type="file" id="qualification_file"class="hidden" accept=".jpg,.jpeg,.png,.pdf">
                  </label>
                  <p id="file_name_qualification" class="text-sm text-gray-600 mt-2"></p> <!-- File name display -->
                </div>
              </div>
            </div>
          </div>
          <!-- Account Information -->
          <div>
            <h3 class="text-lg font-bold text-govt-dark-blue mb-4 pb-2 border-b border-gray-200">Create Account</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Username <span class="text-red-500">*</span></label>
                <input type="text" id="username" required readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
            </div>

              <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium mb-1">Password <span class="text-red-500">*</span></label>
                  <input type="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue">
                  <p class="text-xs text-govt-text-gray mt-1">Must be at least 8 characters with uppercase, lowercase, number</p>
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Confirm Password <span class="text-red-500">*</span></label>
                  <input type="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue">
                </div>
              </div>
            </div>
          </div>

          <!-- Verification and Terms -->
          <div class="space-y-4">
            <div class="bg-govt-light-blue p-4 rounded-md border-l-4 border-govt-blue">
              <div class="flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-govt-blue mr-3 shrink-0">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="16" x2="12" y2="12"></line>
                  <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <div>
                  <p class="text-sm">Your application will be reviewed by the Chief Development Officer (CDO). You will receive an email notification once your account is approved.</p>
                </div>
              </div>
            </div>
            <div class="flex items-start">
              <input type="checkbox" required id="terms" class="mt-1 mr-2">
              <label for="terms" class="text-sm text-govt-text-gray">I hereby declare that all the information provided is correct to the best of my knowledge. I agree to the <a href="#" class="text-govt-blue hover:underline">Terms and Conditions</a> and <a href="#" class="text-govt-blue hover:underline">Privacy Policy</a>.</label>
            </div>
          </div>

          <!-- Submit Buttons -->
          <div class="flex flex-col sm:flex-row sm:justify-between gap-3">
            <button type="reset" class="px-6 py-2 rounded-md border border-gray-300 text-govt-text-gray hover:bg-gray-50 transition-colors">
              Reset Form
            </button>
            <button type="submit" class="px-6 py-2 rounded-md bg-govt-blue text-white hover:bg-govt-dark-blue transition-colors">
              Submit Registration
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <!-- Notification for successful submission - hidden by default -->
  <div id="notification" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-md">
      <div class="flex items-center justify-center mb-4">
        <div class="rounded-full bg-govt-light-green p-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-govt-green">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
        </div>
      </div>
      <h3 class="text-xl font-bold text-govt-dark-blue text-center mb-2">Registration Submitted</h3>
      <p class="text-center text-govt-text-gray mb-4">Your application has been successfully submitted for review by the CDO. You will receive an email notification once your account is approved.</p>
      <p class="text-center text-govt-text-gray mb-6">Your Application ID: <span class="font-bold">SUP-2025-7842</span></p>
      <div class="flex justify-center">
        <button class="px-6 py-2 rounded-md bg-govt-blue text-white hover:bg-govt-dark-blue transition-colors" onclick="document.getElementById('notification').classList.add('hidden')">
          Close
        </button>
      </div>
    </div>
  </div>

  <!-- Footer - Simplified version of the footer we created earlier -->
  <?php include 'includes/footer.php'; ?>

  <!-- Simple script to show notification (for demo purposes) -->
  <script>
    document.getElementById("email").addEventListener("input", function () {
        document.getElementById("username").value = this.value;
    });
  </script>
  <script>
    document.querySelector('form').addEventListener('submit', function(e) {
      e.preventDefault();
      document.getElementById('notification').classList.remove('hidden');
    });
  </script>
  <script>
  document.getElementById("photo-id").addEventListener("change", function () {
    if (this.files.length > 0) {
      document.getElementById("file-name").textContent = "Uploaded File: " + this.files[0].name;
    } else {
      document.getElementById("file-name").textContent = "";
    }
  });
</script>
<script>
  document.getElementById("qualification_file").addEventListener("change", function () {
    if (this.files.length > 0) {
      document.getElementById("file_name_qualification").textContent = "Uploaded File: " + this.files[0].name;
    } else {
      document.getElementById("file_name_qualification").textContent = "";
    }
  });
</script>  
</body>
</html>