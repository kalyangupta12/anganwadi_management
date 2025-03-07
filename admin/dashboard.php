<?php
session_start();

// Check if the session email is set, if not, redirect to login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include('includes/db_connect.php'); // Ensure this file has proper connection details

// Ensure connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Use prepared statements to prevent SQL injection
$user_email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT cdo_username FROM cdo WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists and fetch the username
if ($result && mysqli_num_rows($result) > 0) {
    $row = $result->fetch_assoc();
    $user_name = $row['cdo_username']; // Assign user_name from the result
} else {
    // Handle case where no result is found (optional)
    $user_name = "Guest"; // Default value if no user is found
}

// Close the statement and connection (optional for cleanup)
$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pratham Path Dashboard</title>
  <!-- Tailwind CSS Play CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Configure Tailwind -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'pratham-navy': '#0f172a', 
            'pratham-blue': '#1e40af',
            'pratham-lightblue': '#dbeafe',
            'pratham-skyblue': '#bfdbfe',
          },
          animation: {
            'fade-in': 'fadeIn 0.3s ease-in-out',
            'scale-in': 'scaleIn 0.3s ease-in-out',
          },
          keyframes: {
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' },
            },
            scaleIn: {
              '0%': { transform: 'scale(0.95)', opacity: '0' },
              '100%': { transform: 'scale(1)', opacity: '1' },
            },
          },
        }
      }
    }
  </script>
  <!-- Custom Styles -->
  <style>
    body {
      font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }
    .btn-primary {
      @apply bg-pratham-navy text-white hover:bg-pratham-blue transition-colors duration-300 rounded-md px-4 py-2;
    }
    .btn-secondary {
      @apply bg-white text-pratham-navy border border-pratham-navy hover:bg-pratham-skyblue transition-colors duration-300 rounded-md px-4 py-2;
    }
  </style>
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="bg-pratham-navy text-white w-64 transition-all duration-300 fixed h-full z-10">
      <div class="flex justify-between items-center p-4 border-b border-pratham-blue/20">
        <h2 class="font-bold" id="sidebar-title">Pratham Path</h2>
        <button id="toggle-sidebar" class="p-2 rounded-md hover:bg-pratham-blue/20">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>
      
      <!-- User Info -->
      <div class="p-4 border-b border-pratham-blue/20">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-pratham-blue rounded-full flex items-center justify-center">
            <span class="text-white font-semibold" id="user-avatar">CDO</span>
          </div>
          <div id="user-info">
            <p class="font-medium"><?php echo htmlspecialchars($user_name) ?></p>
            <p class="font-medium" id="user-role-display" style="display: none;"></p>
            <p class="text-xs text-gray-300"><?php echo htmlspecialchars($user_email) ?></p>
            <p class="text-xs text-gray-300" id="user-center" style="display: none;">Centre #1234</p>
          </div>
        </div>
        
        <!-- Role Switcher (for demo only) -->
        <!-- <div class="mt-3" id="role-switcher-container">
          <select
            id="role-switcher"
            class="w-full bg-pratham-blue/20 border border-pratham-blue/30 text-white rounded-md text-sm p-1"
          >
            <option value="worker">Worker View</option>
            <option value="supervisor">Supervisor View</option>
            <option value="admin">CDPO/Admin View</option>
          </select>
        </div> -->
      </div>
      
      <!-- Navigation -->
      <nav class="p-2">
        <ul class="space-y-1">
          <li>
            <button 
              id="nav-beneficiaries"
              class="flex items-center w-full p-3 rounded-md bg-pratham-blue text-white"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              <span class="ml-3">Beneficiaries</span>
            </button>
          </li>
          <li>
            <button 
              id="nav-attendance"
              class="flex items-center w-full p-3 rounded-md hover:bg-pratham-blue/20"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M15 2H9a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1Z"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
              <span class="ml-3">Attendance</span>
            </button>
          </li>
          <li>
            <button 
              id="nav-reports"
              class="flex items-center w-full p-3 rounded-md hover:bg-pratham-blue/20"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
              <span class="ml-3">Reports</span>
            </button>
          </li>
          <li>
            <button 
              id="nav-settings"
              class="flex items-center w-full p-3 rounded-md hover:bg-pratham-blue/20"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
              <span class="ml-3">Settings</span>
            </button>
          </li>
        </ul>
      </nav>
      
      <!-- Logout -->
      <div class="absolute bottom-0 w-full p-4 border-t border-pratham-blue/20">
        <button 
          id="logout-button"
          class="flex items-center w-full p-3 rounded-md hover:bg-pratham-blue/20"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
          <span class="ml-3">Logout</span>
        </button>
      </div>
    </aside>
    
    <!-- Main Content -->
    <main id="main-content" class="flex-1 ml-64 transition-all duration-300">
      <header class="bg-white shadow-sm p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-pratham-navy" id="page-title">
          Beneficiary Management
        </h1>
        
        <button 
          id="home-button"
          class="flex items-center text-gray-600 hover:text-pratham-blue"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-home mr-1"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          <span>Home</span>
        </button>
      </header>
      
      <!-- Content Area -->
      <div class="p-6">
        <!-- Beneficiaries Section -->
        <div id="beneficiaries-section" class="block">
          <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h2 class="text-2xl font-bold text-gray-800">Beneficiary Management</h2>
              <p class="text-gray-600">Manage beneficiaries and their provisions</p>
            </div>
            
            <button 
              id="add-beneficiary-button"
              class="btn-primary flex items-center"
            >
              + Add Beneficiary
            </button>
          </div>
          
          <!-- Add Beneficiary Form -->
          <div id="add-beneficiary-form" class="bg-white p-6 rounded-lg shadow mb-6 hidden animate-fade-in">
            <h3 class="text-lg font-bold mb-4">Add New Beneficiary</h3>
            <form id="beneficiary-form" class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input
                  type="text"
                  required
                  id="beneficiary-name"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                <input
                  type="number"
                  required
                  min="0"
                  max="18"
                  id="beneficiary-age"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                <select
                  id="beneficiary-gender"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                >
                  <option value="Female">Female</option>
                  <option value="Male">Male</option>
                  <option value="Other">Other</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Guardian Name</label>
                <input
                  type="text"
                  required
                  id="beneficiary-guardian"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
              </div>
              
              <div id="student-id-field" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Student ID</label>
                <input
                  type="text"
                  id="beneficiary-student-id"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                />
              </div>
              
              <div class="md:col-span-2 flex justify-end gap-2 mt-4">
                <button 
                  type="button" 
                  id="cancel-beneficiary-button"
                  class="btn-secondary"
                >
                  Cancel
                </button>
                <button type="submit" class="btn-primary">Save Beneficiary</button>
              </div>
            </form>
          </div>
          
          <!-- Beneficiary Types -->
          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex flex-wrap gap-2 mb-6">
              <button
                id="type-children-0-3"
                class="px-4 py-2 rounded-md transition-colors bg-pratham-navy text-white"
              >
                Children (0-3 years)
              </button>
              <button
                id="type-children-3-6"
                class="px-4 py-2 rounded-md transition-colors bg-gray-100 hover:bg-gray-200 text-gray-700"
              >
                Children (3-6 years)
              </button>
              <button
                id="type-girls-15-plus"
                class="px-4 py-2 rounded-md transition-colors bg-gray-100 hover:bg-gray-200 text-gray-700"
              >
                Girls (15+ years)
              </button>
            </div>
            
            <!-- Beneficiaries Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guardian</th>
                    <th id="student-id-header" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden">Student ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provisions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody id="beneficiaries-table-body" class="bg-white divide-y divide-gray-200">
                  <!-- Beneficiaries will be populated here via JavaScript -->
                </tbody>
              </table>
            </div>
            
            <!-- Provisions Section -->
            <div class="mt-6">
              <h3 class="font-medium text-lg">Provisions for <span id="provisions-title">Children (0-3 years)</span></h3>
              <div id="provisions-list" class="mt-2 flex flex-wrap gap-2">
                <span class="bg-pratham-lightblue text-pratham-navy px-3 py-1 rounded-full text-sm">
                  Chawal
                </span>
                <span class="bg-pratham-lightblue text-pratham-navy px-3 py-1 rounded-full text-sm">
                  Dal
                </span>
                <span class="bg-pratham-lightblue text-pratham-navy px-3 py-1 rounded-full text-sm">
                  Matar
                </span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Attendance Section -->
        <div id="attendance-section" class="hidden">
          <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Attendance Management</h2>
            <p class="text-gray-600">Track daily attendance and nutrition distribution</p>
          </div>
          
          <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input
                  type="date"
                  id="attendance-date"
                  class="px-3 py-2 border border-gray-300 rounded-md"
                />
              </div>
              
              <div class="sm:ml-auto">
                <button id="save-attendance-button" class="bg-pratham-navy text-white px-4 py-2 rounded-md hover:bg-pratham-blue transition-colors">
                  Save Attendance
                </button>
              </div>
            </div>
            
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provisions Given</th>
                  </tr>
                </thead>
                <tbody id="attendance-table-body" class="bg-white divide-y divide-gray-200">
                  <!-- Attendance records will be populated here via JavaScript -->
                </tbody>
              </table>
            </div>
            
            <div class="mt-6 flex justify-between items-center">
              <div class="text-sm text-gray-500" id="attendance-summary">
                Total Present: 3 of 5
              </div>
              
              <button id="generate-report-button" class="bg-pratham-navy text-white px-4 py-2 rounded-md hover:bg-pratham-blue transition-colors">
                Generate Report
              </button>
            </div>
          </div>
        </div>
        
        <!-- Reports Section -->
        <div id="reports-section" class="hidden">
          <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Reports & Analytics</h2>
            <p class="text-gray-600" id="reports-description">
              View and generate reports for your Anganwadi centre
            </p>
          </div>
          
          <div id="reports-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Report cards will be populated here via JavaScript -->
          </div>
        </div>
        
        <!-- Settings Section -->
        <div id="settings-section" class="hidden">
          <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Settings</h2>
            <p class="text-gray-600">Manage application settings and preferences</p>
          </div>
          
          <div class="bg-white rounded-lg shadow p-6">
            <div id="settings-categories" class="flex flex-wrap gap-4 mb-6">
              <!-- Settings categories will be populated here via JavaScript -->
            </div>
            
            <div class="border-t border-gray-200 pt-6">
              <h3 class="font-bold text-lg text-pratham-navy mb-4">Profile Settings</h3>
              
              <form id="profile-settings-form" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                  <input
                    type="text"
                    id="profile-name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                    value="Anganwadi Worker"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input
                    type="email"
                    id="profile-email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                    value="worker@prathampath.org"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                  <input
                    type="tel"
                    id="profile-mobile"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                    value="9876543210"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Language Preference</label>
                  <select id="profile-language" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option>English</option>
                    <option>Hindi</option>
                    <option>Assamese</option>
                    <option>Bengali</option>
                  </select>
                </div>
                
                <div class="md:col-span-2">
                  <button type="submit" class="bg-pratham-navy text-white px-4 py-2 rounded-md hover:bg-pratham-blue transition-colors">
                    Save Changes
                  </button>
                </div>
              </form>
            </div>
            
            <div id="user-management-section" class="hidden border-t border-gray-200 pt-6 mt-6">
              <h3 class="font-bold text-lg text-pratham-navy mb-4">User Management</h3>
              
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Centre/Sector</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="px-6 py-4 whitespace-nowrap">Priya Sharma</td>
                      <td class="px-6 py-4 whitespace-nowrap">Anganwadi Worker</td>
                      <td class="px-6 py-4 whitespace-nowrap">Centre #101</td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-2">
                          <button class="text-blue-600 hover:text-blue-800">Edit</button>
                          <button class="text-red-600 hover:text-red-800">Deactivate</button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="px-6 py-4 whitespace-nowrap">Rajesh Kumar</td>
                      <td class="px-6 py-4 whitespace-nowrap">Supervisor</td>
                      <td class="px-6 py-4 whitespace-nowrap">Sector #3</td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-2">
                          <button class="text-blue-600 hover:text-blue-800">Edit</button>
                          <button class="text-red-600 hover:text-red-800">Deactivate</button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <button class="mt-4 text-pratham-blue hover:underline flex items-center">
                + Add New User
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- JavaScript for Dashboard Functionality -->
  <script src="dashboard.js"></script>
</body>
</html>