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
$stmt = $conn->prepare("SELECT cdo_username, cdo_id FROM cdo WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists and fetch the username
if ($result && mysqli_num_rows($result) > 0) {
    $row = $result->fetch_assoc();
    $user_name = $row['cdo_username']; 
    $user_id = $row['cdo_id']; // Assign user_name from the result
} else {
    // Handle case where no result is found (optional)
    $user_name = "Guest"; // Default value if no user is found
    $user_id = -1;
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
  <title>CDO Admin Panel</title>
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
            'govt-green': '#138808'
          }
        }
      }
    }
  </script>
</head>
<body class="bg-gray-50">
  <div class="min-h-screen flex flex-col md:flex-row">
    <!-- Sidebar -->
    <aside class="bg-govt-dark-blue text-white w-full md:w-64">
      <div class="p-4">
        <h2 class="text-xl font-bold">CDO Admin Panel</h2>
        <p class="text-sm mt-2">Welcome, <?php echo htmlspecialchars($user_name); ?></p>
      </div>
      <nav class="mt-4">
        <a href="#supervisors" class="block p-3 hover:bg-govt-blue">Supervisors</a>
        <a href="#centres" class="block p-3 hover:bg-govt-blue">Anganwadi Centres</a>
        <a href="#notifications" class="block p-3 hover:bg-govt-blue">Notifications</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-4 md:p-8">
      <!-- Supervisors Section -->
      <section id="supervisors" class="mb-12">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-govt-dark-blue">Manage Supervisors</h2>
          <button class="bg-govt-blue text-white px-4 py-2 rounded hover:bg-blue-700 mt-4 md:mt-0" onclick="openModal('supervisorModal')">
            + Add Supervisor
          </button>
        </div>

        <!-- Supervisors Table -->
        <div class="bg-white rounded shadow overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-govt-light-blue">
              <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Aadhar</th>
                <th class="px-4 py-2">Contact</th>
                <th class="px-4 py-2">Verified</th>
                <th class="px-4 py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Data will be populated via PHP -->
              <?php include 'includes/admin_handler.php'; echo fetchSupervisors(); ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Anganwadi Centres Section -->
      <section id="centres" class="mb-12">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-govt-dark-blue">Manage Centres</h2>
          <button class="bg-govt-blue text-white px-4 py-2 rounded hover:bg-blue-700 mt-4 md:mt-0" onclick="openModal('centreModal')">
            + Add Centre
          </button>
        </div>

        <!-- Centres Table -->
        <div class="bg-white rounded shadow overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-govt-light-blue">
              <tr>
                <th class="px-4 py-2">Centre ID</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Location</th>
                <th class="px-4 py-2">Supervisor</th>
                <th class="px-4 py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Data will be populated via PHP -->
              <?php echo fetchCentres(); ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Notifications Section -->
      <section id="notifications">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-govt-dark-blue">Manage Notifications</h2>
          <button class="bg-govt-blue text-white px-4 py-2 rounded hover:bg-blue-700 mt-4 md:mt-0" onclick="openModal('notificationModal')">
            + Create Notification
          </button>
        </div>

        <!-- Notifications Table -->
        <div class="bg-white rounded shadow overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-govt-light-blue">
              <tr>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Recipient</th>
                <th class="px-4 py-2">Message</th>
                <th class="px-4 py-2">Status</th>
              </tr>
            </thead>
            <tbody>
              <!-- Data will be populated via PHP -->
              <?php echo fetchNotifications(); ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>

  <!-- Modals -->
  <?php include 'includes/modals.php'; ?>

  <script>
    // Modal handling
    function openModal(modalId) {
      document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
      document.getElementById(modalId).classList.add('hidden');
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
      if (event.target.classList.contains('fixed')) {
        document.querySelectorAll('.fixed').forEach(modal => modal.classList.add('hidden'));
      }
    }
  </script>
  <script>
  // Supervisor CRUD
  function editSupervisor(id) {
    fetch(`includes/admin_handler.php?action=getSupervisor&id=${id}`)
      .then(response => response.json())
      .then(data => {
        document.getElementById('editSupervisorId').value = data.supervisor_id;
        document.getElementById('editName').value = data.name;
        document.getElementById('editAadhar').value = data.aadhar;
        document.getElementById('editContact').value = data.contact_number;
        openModal('editSupervisorModal');
      });
  }

  function deleteSupervisor(id) {
    if (confirm('Are you sure you want to delete this supervisor?')) {
      fetch('includes/admin_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'deleteSupervisor', id: id })
      })
      .then(response => response.json())
      .then(data => {
        alert(data.message);
        location.reload();
      });
    }
  }
  
  document.getElementById('addSupervisorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('user_id', '<?php echo htmlspecialchars($user_id, ENT_QUOTES, "UTF-8"); ?>');

    fetch('includes/admin_handler.php?action=addSupervisor', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      alert(data.message);
      if (data.status === 'success') location.reload();
    })
    .catch(error => console.error('Error:', error));
  });

  document.getElementById('editSupervisorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('includes/admin_handler.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      alert(data.message);
      if (data.status === 'success') location.reload();
    });
  });
  
  // Similar functions for Centres and Notifications
  </script>
</body>
</html>