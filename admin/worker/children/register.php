<?php
// children/register.php

require '../../includes/auth.php';
require '../../includes/db.php';
require '../../includes/functions.php';
redirectIfNotWorker();

$user_id = $_SESSION['user_id'];

// Fetch worker's assigned centre
$stmt = $pdo->prepare("SELECT centre_id FROM workers WHERE user_id = ?");
$stmt->execute([$user_id]);
$worker = $stmt->fetch();

if (!$worker) {
    header('Location: ../../login.php');
    exit();
}

$centre_id = $worker['centre_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $aadhar_card = $_POST['aadhar_card'];
    $aadhar_type = $_POST['aadhar_type'];
    $parent_name = $_POST['parent_name'];
    $contact_number = $_POST['contact_number'];
    $image_path = '';

    // Handle base64 image data
    if (!empty($_POST['image_data'])) {
        $image_data = $_POST['image_data'];
        $upload_dir = '../../uploads/children/';
        $image_name = uniqid() . '.png'; // Save as PNG
        $image_path = $upload_dir . $image_name;

        // Convert base64 data to image file
        list($type, $data) = explode(';', $image_data);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);

        file_put_contents($image_path, $data);
    }

    // Insert child data into the database
    $stmt = $pdo->prepare("
        INSERT INTO children (centre_id, name, dob, gender, image_path, aadhar_card, aadhar_type, parent_name, contact_number)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $centre_id, $name, $dob, $gender, $image_path, $aadhar_card, $aadhar_type, $parent_name, $contact_number
    ]);

    header('Location: view.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Child</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Register Child</h1>
        <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            <!-- Existing form fields -->
            <div class="mb-4">
                <label class="block text-gray-700">Name</label>
                <input type="text" name="name" required class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Date of Birth</label>
                <input type="date" name="dob" required class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Gender</label>
                <select name="gender" required class="w-full p-2 border rounded">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Aadhar Card Number</label>
                <input type="text" name="aadhar_card" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Aadhar Type</label>
                <select name="aadhar_type" class="w-full p-2 border rounded">
                    <option value="Child">Child</option>
                    <option value="Father">Father</option>
                    <option value="Mother">Mother</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Parent Name</label>
                <input type="text" name="parent_name" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Contact Number</label>
                <input type="text" name="contact_number" class="w-full p-2 border rounded">
            </div>

            <!-- Webcam and Live Image Capture -->
            <div class="mb-4">
                <label class="block text-gray-700">Child Image</label>
                <div id="webcam-container" class="mb-2">
                    <video id="webcam" width="320" height="240" autoplay class="border rounded"></video>
                </div>
                <button type="button" id="captureButton" class="bg-green-500 text-white p-2 rounded">Capture Live Image</button>
                <canvas id="canvas" style="display: none;"></canvas>
                <input type="hidden" id="imageData" name="image_data">
            </div>

            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Register</button>
        </form>
    </div>

    <script>
        // Access the webcam
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const captureButton = document.getElementById('captureButton');
        const imageDataInput = document.getElementById('imageData');

        navigator.mediaDevices.getUserMedia({ video: true, audio: false })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error("Error accessing webcam:", err);
                alert("Failed to access webcam. Please ensure your camera is connected and permissions are granted.");
            });

        // Capture image from webcam
        captureButton.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convert canvas image to base64 data URL
            const imageData = canvas.toDataURL('image/png');
            imageDataInput.value = imageData; // Store base64 data in hidden input

            // Show a preview of the captured image
            const preview = document.createElement('img');
            preview.src = imageData;
            preview.width = 160;
            preview.height = 120;
            preview.classList.add('border', 'rounded', 'mt-2');

            const webcamContainer = document.getElementById('webcam-container');
            webcamContainer.appendChild(preview);
        });
    </script>
</body>
</html>