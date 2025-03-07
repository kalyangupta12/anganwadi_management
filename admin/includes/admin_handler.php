<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prathampath_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


// Helper function to sanitize input
function sanitizeInput($data) {
  global $conn;
  return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

// CRUD Functions for supervisors
function fetchSupervisors() {
  global $conn;
  $sql = "SELECT * FROM supervisors";
  $result = $conn->query($sql);
  $output = "";
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $output .= "<tr class='border-b'>
        <td class='px-4 py-2'>{$row['supervisor_id']}</td>
        <td class='px-4 py-2'>{$row['name']}</td>
        <td class='px-4 py-2'>{$row['aadhar']}</td>
        <td class='px-4 py-2'>{$row['contact_number']}</td>
        <td class='px-4 py-2'>" . ($row['verified'] ? "Verified" : "Pending") . "</td>
        <td class='px-4 py-2 space-x-2'>
          <button onclick='editSupervisor({$row['supervisor_id']})' class='text-govt-blue hover:underline'>Edit</button>
          <button onclick='deleteSupervisor({$row['supervisor_id']})' class='text-red-600 hover:underline'>Delete</button>
        </td>
      </tr>";
    }
  } else {
    $output = "<tr><td colspan='6' class='text-center py-4'>No supervisors found</td></tr>";
  }
  return $output;
}

function addSupervisor($data) {
    global $conn;

    $user_id = $data['user_id'];
    $name = $data['name'];
    $aadhar = $data['aadhar'];
    $contact = $data['contact'];

    $sql = "INSERT INTO supervisors (cdo_id, name, aadhar, contact_number) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssss", $user_id, $name, $aadhar, $contact);
        if ($stmt->execute()) {
            return true; // Successfully inserted
        }
        $stmt->close();
    }

    return false; // Insert failed
}

function editSupervisor($id, $data) {
  global $conn;
  $name = sanitizeInput($data['name']);
  $aadhar = sanitizeInput($data['aadhar']);
  $contact = sanitizeInput($data['contact']);
  $sql = "UPDATE supervisors SET name='$name', aadhar='$aadhar', contact_number='$contact' WHERE supervisor_id=$id";
  return $conn->query($sql);
}

function deleteSupervisor($id) {
  global $conn;
  $sql = "DELETE FROM supervisors WHERE supervisor_id=$id";
  return $conn->query($sql);
}

// CRUD Functions for Anganwadi Centres
function fetchCentres() {
  global $conn;
  $sql = "SELECT * FROM AnganwadiCentres";
  $result = $conn->query($sql);
  $output = "";
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $output .= "<tr class='border-b'>
        <td class='px-4 py-2'>{$row['centre_id']}</td>
        <td class='px-4 py-2'>{$row['centre_name']}</td>
        <td class='px-4 py-2'>{$row['location']}</td>
        <td class='px-4 py-2'>{$row['supervisor_id']}</td>
        <td class='px-4 py-2 space-x-2'>
          <button onclick='editCentre({$row['centre_id']})' class='text-govt-blue hover:underline'>Edit</button>
          <button onclick='deleteCentre({$row['centre_id']})' class='text-red-600 hover:underline'>Delete</button>
        </td>
      </tr>";
    }
  } else {
    $output = "<tr><td colspan='5' class='text-center py-4'>No centres found</td></tr>";
  }
  return $output;
}

function addCentre($data) {
  global $conn;
  $name = sanitizeInput($data['name']);
  $location = sanitizeInput($data['location']);
  $supervisor_id = sanitizeInput($data['supervisor_id']);
  $sql = "INSERT INTO AnganwadiCentres (centre_name, location, supervisor_id) VALUES ('$name', '$location', '$supervisor_id')";
  return $conn->query($sql);
}

function editCentre($id, $data) {
  global $conn;
  $name = sanitizeInput($data['name']);
  $location = sanitizeInput($data['location']);
  $supervisor_id = sanitizeInput($data['supervisor_id']);
  $sql = "UPDATE AnganwadiCentres SET centre_name='$name', location='$location', supervisor_id='$supervisor_id' WHERE centre_id=$id";
  return $conn->query($sql);
}

function deleteCentre($id) {
  global $conn;
  $sql = "DELETE FROM AnganwadiCentres WHERE centre_id=$id";
  return $conn->query($sql);
}

// CRUD Functions for Notifications
function fetchNotifications() {
  global $conn;
  $sql = "SELECT * FROM Notifications";
  $result = $conn->query($sql);
  $output = "";
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $output .= "<tr class='border-b'>
        <td class='px-4 py-2'>{$row['datetime']}</td>
        <td class='px-4 py-2'>{$row['recipient_type']}</td>
        <td class='px-4 py-2'>{$row['message']}</td>
        <td class='px-4 py-2'>Sent</td>
      </tr>";
    }
  } else {
    $output = "<tr><td colspan='4' class='text-center py-4'>No notifications found</td></tr>";
  }
  return $output;
}

function addNotification($data) {
  global $conn;
  $message = sanitizeInput($data['message']);
  $recipient_type = sanitizeInput($data['recipient_type']);
  $recipient_id = sanitizeInput($data['recipient_id']);
  $datetime = sanitizeInput($data['datetime']);
  $sql = "INSERT INTO Notifications (message, recipient_type, recipient_id, datetime) VALUES ('$message', '$recipient_type', '$recipient_id', '$datetime')";
  return $conn->query($sql);
}

function deleteNotification($id) {
  global $conn;
  $sql = "DELETE FROM Notifications WHERE notification_id=$id";
  return $conn->query($sql);
}

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = sanitizeInput($_GET['action']);
  $response = [];

  switch ($action) {
    case 'addSupervisor':
      if (addSupervisor($_POST)) {
        $response['status'] = 'success';
        $response['message'] = 'Supervisor added successfully!';
      } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to add supervisor.';
      }
      break;

    case 'editSupervisor':
      $id = sanitizeInput($_POST['id']);
      if (editSupervisor($id, $_POST)) {
        $response['status'] = 'success';
        $response['message'] = 'Supervisor updated successfully!';
      } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to update supervisor.';
      }
      break;

    case 'deleteSupervisor':
      $id = sanitizeInput($_POST['id']);
      if (deleteSupervisor($id)) {
        $response['status'] = 'success';
        $response['message'] = 'Supervisor deleted successfully!';
      } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to delete supervisor.';
      }
      break;

    // Similar cases for Centres and Notifications
  }

  echo json_encode($response);
  exit;
}
?>