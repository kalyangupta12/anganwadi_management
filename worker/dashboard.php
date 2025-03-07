<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Anganwadi Worker Dashboard</title>
  <!-- Tailwind Play CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
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
        <a href="index.html" class="flex items-center gap-2">
          <img 
            src="/lovable-uploads/cc2ff230-e81f-49d7-8cdf-ebc2b1acf593.png" 
            alt="Logo" 
            class="h-12"
          />
          <div class="text-white text-xl font-semibold">Anganwadi Worker Portal</div>
        </a>
        
        <div class="flex items-center gap-4">
          <span class="text-white">Welcome, Worker</span>
          <button class="bg-white text-govt-blue px-4 py-2 rounded hover:bg-gray-100 transition-colors">
            Logout
          </button>
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
          <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
            <div class="flex gap-2">
              <button id="toggleChildForm" class="bg-govt-blue text-white px-4 py-2 rounded hover:bg-govt-dark-blue transition-colors">
                Register New Child
              </button>
              <button id="toggleScanner" class="border-govt-blue text-govt-blue border px-4 py-2 rounded hover:bg-govt-light-blue transition-colors">
                Face Registration
              </button>
            </div>
            <div class="w-full md:w-1/3">
              <input 
                type="search" 
                placeholder="Search by name, ID or parent name" 
                id="childSearch"
                class="w-full h-10 rounded-md border border-gray-300 px-3 py-2"
              />
            </div>
          </div>

          <!-- Register New Child Form (hidden by default) -->
          <div id="childForm" class="mb-6 border-govt-blue border-t-4 bg-white shadow-md rounded-lg hidden">
            <div class="p-4 border-b">
              <h2 class="text-xl font-bold text-govt-blue">Register New Child</h2>
            </div>
            <div class="p-6">
              <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium mb-1">Child ID</label>
                  <input type="text" placeholder="Dist+Centre+UniqueNo" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Full Name</label>
                  <input type="text" placeholder="Full Name" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Date of Birth</label>
                  <input type="date" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Gender</label>
                  <select class="w-full h-10 rounded-md border border-gray-300 px-3 py-2">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Aadhar Card</label>
                  <input type="text" placeholder="12-digit Aadhar Number" maxlength="12" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Aadhar Card Type</label>
                  <select class="w-full h-10 rounded-md border border-gray-300 px-3 py-2">
                    <option value="">Select Type</option>
                    <option value="Child">Child</option>
                    <option value="Father">Father</option>
                    <option value="Mother">Mother</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Parent Name</label>
                  <input type="text" placeholder="Parent/Guardian Name" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Contact Number</label>
                  <input type="tel" placeholder="Contact Number" maxlength="10" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" />
                </div>
                <div class="md:col-span-2 mt-4">
                  <button type="submit" class="bg-govt-blue text-white px-4 py-2 rounded hover:bg-govt-dark-blue transition-colors">Register Child</button>
                </div>
              </form>
            </div>
          </div>

          <!-- Face Registration Form (hidden by default) -->
          <div id="scannerForm" class="mb-6 border-govt-blue border-t-4 bg-white shadow-md rounded-lg hidden">
            <div class="p-4 border-b">
              <h2 class="text-xl font-bold text-govt-blue">Face Registration</h2>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                  <div class="border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center" style="height: 300px">
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
                    <button class="bg-govt-blue text-white px-4 py-2 rounded hover:bg-govt-dark-blue transition-colors">Start Camera</button>
                    <button class="border-govt-blue text-govt-blue border px-4 py-2 rounded hover:bg-govt-light-blue transition-colors">Take Photo</button>
                  </div>
                </div>
                
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium mb-1">Select Child</label>
                    <select class="w-full h-10 rounded-md border border-gray-300 px-3 py-2">
                      <option value="">Select Child ID</option>
                      <option value="DL001C001">DL001C001 - Aarav Sharma</option>
                      <option value="DL001C002">DL001C002 - Priya Patel</option>
                      <option value="DL001C003">DL001C003 - Arjun Singh</option>
                    </select>
                  </div>
                  <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 h-48 flex items-center justify-center">
                    <div class="text-center">
                      <p class="text-govt-blue font-medium">Captured photo will appear here</p>
                    </div>
                  </div>
                  <button class="w-full bg-govt-blue text-white px-4 py-2 rounded hover:bg-govt-dark-blue transition-colors">Register Face ID</button>
                </div>
              </div>
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
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent Name</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aadhar</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200" id="childrenTableBody">
                    <!-- Child rows will be populated by JavaScript -->
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
        <!-- Ration Distribution Content -->
        <div class="space-y-6">
          <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
            <button id="toggleRationForm" class="w-full md:w-auto bg-govt-blue text-white px-4 py-2 rounded hover:bg-govt-dark-blue transition-colors">
              Record New Distribution
            </button>
            <div class="w-full md:w-1/3">
              <input 
                type="search" 
                placeholder="Search by child name, ID or ration type" 
                id="rationSearch"
                class="w-full h-10 rounded-md border border-gray-300 px-3 py-2"
              />
            </div>
          </div>

          <!-- Ration Distribution Form (hidden by default) -->
          <div id="rationForm" class="mb-6 border-govt-blue border-t-4 bg-white shadow-md rounded-lg hidden">
            <div class="p-4 border-b">
              <h2 class="text-xl font-bold text-govt-blue">Record Ration Distribution</h2>
            </div>
            <div class="p-6">
              <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium mb-1">Child</label>
                  <select class="w-full h-10 rounded-md border border-gray-300 px-3 py-2">
                    <option value="">Select Child</option>
                    <option value="DL001C001">DL001C001 - Aarav Sharma</option>
                    <option value="DL001C002">DL001C002 - Priya Patel</option>
                    <option value="DL001C003">DL001C003 - Arjun Singh</option>
                    <option value="DL001C004">DL001C004 - Ishita Verma</option>
                    <option value="DL001C005">DL001C005 - Rohan Kumar</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Date & Time</label>
                  <input type="datetime-local" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Ration Type</label>
                  <select class="w-full h-10 rounded-md border border-gray-300 px-3 py-2">
                    <option value="">Select Ration Type</option>
                    <option value="Chawal">Chawal</option>
                    <option value="Dal">Dal</option>
                    <option value="Matar">Matar</option>
                    <option value="Khichdi">Khichdi</option>
                    <option value="Sooji">Sooji</option>
                    <option value="Horlicks">Horlicks</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Quantity (kg/liter)</label>
                  <input type="number" step="0.1" min="0" placeholder="Quantity" class="w-full h-10 rounded-md border border-gray-300 px-3 py-2" />
                </div>
                <div class="md:col-span-2 mt-4">
                  <button type="submit" class="bg-govt-blue text-white px-4 py-2 rounded hover:bg-govt-dark-blue transition-colors">Record Distribution</button>
                </div>
              </form>
            </div>
          </div>

          <!-- Ration Distribution Records Table -->
          <div class="bg-white shadow-md rounded-lg">
            <div class="p-4 border-b">
              <h2 class="text-xl font-bold">Ration Distribution Records</h2>
            </div>
            <div class="p-6">
              <div class="rounded-md border overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Child ID</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Child Name</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ration Type</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distributed By</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200" id="rationTableBody">
                    <!-- Ration rows will be populated by JavaScript -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Attendance Tab -->
      <input type="radio" name="tabs" id="tab-attendance" class="tab-input">
      <label for="tab-attendance" class="tab-label text-lg py-3 w-1/3 text-center">Attendance</label>
      <div class="tab-content p-4">
        <!-- Attendance Content -->
        <div class="space-y-6">
          <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-2">
              <input type="date" id="attendanceDate" class="w-full sm:w-auto rounded-md border border-gray-300 px-3 py-2" />
              <button class="bg-govt-blue text-white px-4 py-2 rounded hover:bg-govt-dark-blue transition-colors">
                Take Attendance
              </button>
            </div>
            <div class="w-full md:w-1/3">
              <input 
                type="search" 
                placeholder="Search by name or ID" 
                id="attendanceSearch"
                class="w-full h-10 rounded-md border border-gray-300 px-3 py-2"
              />
            </div>
          </div>

          <!-- Attendance Stats Cards -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 shadow rounded-md border-l-4 border-green-500">
              <div class="flex items-center">
                <div class="mr-4 bg-green-100 p-3 rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <div>
                  <p class="text-gray-500 text-sm">Present Today</p>
                  <p class="text-2xl font-bold">18</p>
                </div>
              </div>
            </div>
            
            <div class="bg-white p-4 shadow rounded-md border-l-4 border-red-500">
              <div class="flex items-center">
                <div class="mr-4 bg-red-100 p-3 rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </div>
                <div>
                  <p class="text-gray-500 text-sm">Absent Today</p>
                  <p class="text-2xl font-bold">7</p>
                </div>
              </div>
            </div>
            
            <div class="bg-white p-4 shadow rounded-md border-l-4 border-blue-500">
              <div class="flex items-center">
                <div class="mr-4 bg-blue-100 p-3 rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-gray-500 text-sm">Monthly Attendance</p>
                  <p class="text-2xl font-bold">72%</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Attendance Records Table -->
          <div class="bg-white shadow-md rounded-lg">
            <div class="p-4 border-b">
              <h2 class="text-xl font-bold">Attendance Records</h2>
              <p class="text-sm text-gray-500">Showing records for Today</p>
            </div>
            <div class="p-6">
              <div class="rounded-md border overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marked By</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200" id="attendanceTableBody">
                    <!-- Attendance rows will be populated by JavaScript -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-govt-blue text-white py-6">
    <div class="container mx-auto px-4">
      <div class="tricolor-border mb-6"></div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
          <h3 class="text-lg font-semibold mb-4">Anganwadi Services</h3>
          <ul class="space-y-2">
            <li>Child Health & Nutrition</li>
            <li>Pre-school Education</li>
            <li>Immunization</li>
            <li>Health Check-ups</li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
          <ul class="space-y-2">
            <li><a href="index.html" class="hover:underline">Home</a></li>
            <li><a href="attendance.html" class="hover:underline">Attendance</a></li>
            <li><a href="supervisor-registration.html" class="hover:underline">Supervisor Registration</a></li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">Contact</h3>
          <ul class="space-y-2">
            <li>Email: info@anganwadi.gov.in</li>
            <li>Phone: 1800-XXX-XXXX</li>
            <li>Working Hours: 9 AM - 5 PM</li>
          </ul>
        </div>
      </div>
      <div class="mt-8 pt-4 border-t border-blue-800 text-center">
        <p>© 2023 Anganwadi Services. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script>
    // Mock data for children
    const children = [
      { 
        child_id: 'DL001C001', 
        name: 'Aarav Sharma', 
        dob: '2019-05-12', 
        gender: 'Male',
        aadhar_card: '123456789012',
        aadhar_type: 'Child',
        parent_name: 'Raj Sharma',
        contact_number: '9876543210'
      },
      { 
        child_id: 'DL001C002', 
        name: 'Priya Patel', 
        dob: '2020-03-15', 
        gender: 'Female',
        aadhar_card: '234567890123',
        aadhar_type: 'Mother',
        parent_name: 'Meena Patel',
        contact_number: '8765432109'
      },
      { 
        child_id: 'DL001C003', 
        name: 'Arjun Singh', 
        dob: '2018-11-20', 
        gender: 'Male',
        aadhar_card: '345678901234',
        aadhar_type: 'Father',
        parent_name: 'Gurpreet Singh',
        contact_number: '7654321098'
      },
    ];

    // Mock data for ration distribution
    const rationDistributions = [
      { 
        ration_id: 1, 
        child_id: 'DL001C001', 
        child_name: 'Aarav Sharma',
        datetime: '2023-08-15 10:30:00', 
        ration_type: 'Chawal', 
        quantity: 2.5,
        distributed_by: 'Kavita Kumari'
      },
      { 
        ration_id: 2, 
        child_id: 'DL001C002', 
        child_name: 'Priya Patel',
        datetime: '2023-08-15 11:15:00', 
        ration_type: 'Dal', 
        quantity: 1.0,
        distributed_by: 'Kavita Kumari'
      },
      { 
        ration_id: 3, 
        child_id: 'DL001C003', 
        child_name: 'Arjun Singh',
        datetime: '2023-08-15 12:00:00', 
        ration_type: 'Khichdi', 
        quantity: 1.5,
        distributed_by: 'Kavita Kumari'
      },
      { 
        ration_id: 4, 
        child_id: 'DL001C001', 
        child_name: 'Aarav Sharma',
        datetime: '2023-08-25 10:45:00', 
        ration_type: 'Sooji', 
        quantity: 1.0,
        distributed_by: 'Kavita Kumari'
      },
    ];

    // Mock data for attendance
    const attendanceRecords = [
      {
        id: 1,
        child_id: 'DL001C001',
        name: 'Aarav Sharma',
        time: '09:15 AM',
        status: 'Present',
        marked_by: 'Kavita Kumari'
      },
      {
        id: 2,
        child_id: 'DL001C002',
        name: 'Priya Patel',
        time: '09:20 AM',
        status: 'Present',
        marked_by: 'Kavita Kumari'
      },
      {
        id: 3,
        child_id: 'DL001C003',
        name: 'Arjun Singh',
        time: '09:30 AM',
        status: 'Present',
        marked_by: 'Kavita Kumari'
      },
      {
        id: 4,
        child_id: 'DL001C004',
        name: 'Ishita Verma',
        time: '00:00',
        status: 'Absent',
        marked_by: 'Kavita Kumari'
      },
      {
        id: 5,
        child_id: 'DL001C005',
        name: 'Rohan Kumar',
        time: '09:45 AM',
        status: 'Present',
        marked_by: 'Kavita Kumari'
      }
    ];

    // Helper functions
    function calculateAge(dob) {
      const birthDate = new Date(dob);
      const today = new Date();
      let age = today.getFullYear() - birthDate.getFullYear();
      const monthDiff = today.getMonth() - birthDate.getMonth();
      
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }
      
      return age;
    }

    function formatDate(dateStr) {
      const date = new Date(dateStr);
      return `${date.toLocaleDateString()} at ${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
    }

    // Populate children table
    function populateChildrenTable(childrenData) {
      const tableBody = document.getElementById('childrenTableBody');
      tableBody.innerHTML = '';
      
      if (childrenData.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td colspan="8" class="px-6 py-4 text-center">No children found</td>
        `;
        tableBody.appendChild(row);
        return;
      }
      
      childrenData.forEach(child => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td class="px-6 py-4 whitespace-nowrap font-medium">${child.child_id}</td>
          <td class="px-6 py-4 whitespace-nowrap">${child.name}</td>
          <td class="px-6 py-4 whitespace-nowrap">${calculateAge(child.dob)} years</td>
          <td class="px-6 py-4 whitespace-nowrap">${child.gender}</td>
          <td class="px-6 py-4 whitespace-nowrap">${child.parent_name}</td>
          <td class="px-6 py-4 whitespace-nowrap">${child.contact_number}</td>
          <td class="px-6 py-4 whitespace-nowrap">${child.aadhar_card.substring(0, 4) + 'xxxx' + child.aadhar_card.substring(8)} (${child.aadhar_type})</td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex gap-2">
              <button class="border-govt-blue text-govt-blue border px-3 py-1 text-sm rounded hover:bg-govt-light-blue transition-colors">Edit</button>
              <button class="border-red-500 text-red-500 border px-3 py-1 text-sm rounded hover:bg-red-50 transition-colors">Delete</button>
            </div>
          </td>
        `;
        tableBody.appendChild(row);
      });
    }

    // Populate ration distribution table
    function populateRationTable(rationData) {
      const tableBody = document.getElementById('rationTableBody');
      tableBody.innerHTML = '';
      
      if (rationData.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td colspan="8" class="px-6 py-4 text-center">No ration distribution records found</td>
        `;
        tableBody.appendChild(row);
        return;
      }
      
      rationData.forEach(ration => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td class="px-6 py-4 whitespace-nowrap">${ration.ration_id}</td>
          <td class="px-6 py-4 whitespace-nowrap">${ration.child_id}</td>
          <td class="px-6 py-4 whitespace-nowrap">${ration.child_name}</td>
          <td class="px-6 py-4 whitespace-nowrap">${formatDate(ration.datetime)}</td>
          <td class="px-6 py-4 whitespace-nowrap">${ration.ration_type}</td>
          <td class="px-6 py-4 whitespace-nowrap">${ration.quantity} kg</td>
          <td class="px-6 py-4 whitespace-nowrap">${ration.distributed_by}</td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex gap-2">
              <button class="border-govt-blue text-govt-blue border px-3 py-1 text-sm rounded hover:bg-govt-light-blue transition-colors">Edit</button>
              <button class="border-red-500 text-red-500 border px-3 py-1 text-sm rounded hover:bg-red-50 transition-colors">Delete</button>
            </div>
          </td>
        `;
        tableBody.appendChild(row);
      });
    }

    // Populate attendance table
    function populateAttendanceTable(attendanceData) {
      const tableBody = document.getElementById('attendanceTableBody');
      tableBody.innerHTML = '';
      
      if (attendanceData.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td colspan="6" class="px-6 py-4 text-center">No attendance records found</td>
        `;
        tableBody.appendChild(row);
        return;
      }
      
      attendanceData.forEach(record => {
        const row = document.createElement('tr');
        const statusClass = record.status === 'Present' ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100';
        
        row.innerHTML = `
          <td class="px-6 py-4 whitespace-nowrap">${record.child_id}</td>
          <td class="px-6 py-4 whitespace-nowrap">${record.name}</td>
          <td class="px-6 py-4 whitespace-nowrap">${record.time}</td>
          <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 py-1 rounded-full text-xs font-medium ${statusClass}">
              ${record.status}
            </span>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">${record.marked_by}</td>
          <td class="px-6 py-4 whitespace-nowrap">
            <button class="border-govt-blue text-govt-blue border px-3 py-1 text-sm rounded hover:bg-govt-light-blue transition-colors">
              Update
            </button>
          </td>
        `;
        tableBody.appendChild(row);
      });
    }

    // Search functionality for children
    document.getElementById('childSearch').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const filteredChildren = children.filter(child => 
        child.name.toLowerCase().includes(searchTerm) || 
        child.child_id.toLowerCase().includes(searchTerm) ||
        child.parent_name.toLowerCase().includes(searchTerm)
      );
      populateChildrenTable(filteredChildren);
    });

    // Search functionality for ration
    document.getElementById('rationSearch').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const filteredRations = rationDistributions.filter(ration => 
        ration.child_name.toLowerCase().includes(searchTerm) || 
        ration.child_id.toLowerCase().includes(searchTerm) ||
        ration.ration_type.toLowerCase().includes(searchTerm)
      );
      populateRationTable(filteredRations);
    });

    // Search functionality for attendance
    document.getElementById('attendanceSearch').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const filteredAttendance = attendanceRecords.filter(record => 
        record.name.toLowerCase().includes(searchTerm) || 
        record.child_id.toLowerCase().includes(searchTerm)
      );
      populateAttendanceTable(filteredAttendance);
    });

    // Toggle forms
    document.getElementById('toggleChildForm').addEventListener('click', function() {
      const form = document.getElementById('childForm');
      const scannerForm = document.getElementById('scannerForm');
      form.classList.toggle('hidden');
      if (!scannerForm.classList.contains('hidden')) {
        scannerForm.classList.add('hidden');
      }
      this.textContent = form.classList.contains('hidden') ? 'Register New Child' : 'Cancel';
    });

    document.getElementById('toggleScanner').addEventListener('click', function() {
      const form = document.getElementById('scannerForm');
      const childForm = document.getElementById('childForm');
      form.classList.toggle('hidden');
      if (!childForm.classList.contains('hidden')) {
        childForm.classList.add('hidden');
        document.getElementById('toggleChildForm').textContent = 'Register New Child';
      }
      this.textContent = form.classList.contains('hidden') ? 'Face Registration' : 'Cancel';
    });

    document.getElementById('toggleRationForm').addEventListener('click', function() {
      const form = document.getElementById('rationForm');
      form.classList.toggle('hidden');
      this.textContent = form.classList.contains('hidden') ? 'Record New Distribution' : 'Cancel';
    });

    // Initialize tables on page load
    document.addEventListener('DOMContentLoaded', function() {
      populateChildrenTable(children);
      populateRationTable(rationDistributions);
      populateAttendanceTable(attendanceRecords);
      
      // Set current date in attendance date picker
      const today = new Date();
      const dateString = today.toISOString().split('T')[0];
      document.getElementById('attendanceDate').value = dateString;
    });
  </script>
</body>
</html>