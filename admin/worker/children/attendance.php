<?php
// children/attendance.php

require '../../includes/auth.php';
require '../../includes/db.php';
require '../../includes/functions.php';
redirectIfNotWorker();

$user_id = $_SESSION['user_id'];

// Fetch worker's assigned centre
$stmt = $pdo->prepare("SELECT * FROM workers WHERE user_id = ?");
$stmt->execute([$user_id]);
$worker = $stmt->fetch();

if (!$worker) {
    header('Location: ../../login.php');
    exit();
}

$centre_id = $worker['centre_id'];
$worker_id = $worker['worker_id'];

// Fetch children for the worker's centre
$stmt = $pdo->prepare("SELECT child_id, name, image_path FROM children WHERE centre_id = ?");
$stmt->execute([$centre_id]);
$children = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="face-api.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Mark Attendance</h1>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div id="video-container" class="mb-4">
                <video id="video" width="640" height="480" autoplay></video>
            </div>
            <button id="startButton" class="bg-blue-500 text-white p-2 rounded">Start Webcam</button>
            <button id="endAttendance" class="bg-red-500 text-white p-2 rounded">End Attendance</button>
        </div>
        <div id="messageDiv" class="mt-4"></div>
    </div>
    <script>
    // Load face-api.js models
    Promise.all([
        faceapi.nets.ssdMobilenetv1.loadFromUri('../../assets/models'),
        faceapi.nets.faceRecognitionNet.loadFromUri('../../assets/models'),
        faceapi.nets.faceLandmark68Net.loadFromUri('../../assets/models')
    ]).then(startWebcam).catch(err => {
        console.error("Error loading models:", err);
        alert("Failed to load models. Please check the console for details.");
    });

    const video = document.getElementById('video');
    const startButton = document.getElementById('startButton');
    const endAttendanceButton = document.getElementById('endAttendance');
    const messageDiv = document.getElementById('messageDiv');
    let videoStream;

    // Start webcam
    function startWebcam() {
        navigator.mediaDevices.getUserMedia({ video: true, audio: false })
            .then(stream => {
                video.srcObject = stream;
                videoStream = stream;
            })
            .catch(err => {
                console.error("Error accessing webcam:", err);
                alert("Failed to access webcam. Please ensure your camera is connected and permissions are granted.");
            });
    }

    // Stop webcam
    function stopWebcam() {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }
    }

    // Load labeled face descriptors
    async function getLabeledFaceDescriptions() {
        const labeledFaceDescriptors = [];
        const children = <?php echo json_encode($children); ?>;

        for (const child of children) {
            const { child_id, image_path } = child;
            const descriptions = [];

            try {
                const img = await faceapi.fetchImage(image_path);
                const detections = await faceapi
                    .detectSingleFace(img)
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (detections) {
                    descriptions.push(detections.descriptor);
                } else {
                    console.log(`No face detected in ${image_path}`);
                }
            } catch (err) {
                console.error(`Error processing ${image_path}:`, err);
            }

            if (descriptions.length > 0) {
                labeledFaceDescriptors.push(
                    new faceapi.LabeledFaceDescriptors(child_id, descriptions)
                );
            }
        }

        return labeledFaceDescriptors;
    }

    // Start facial recognition
    startButton.addEventListener('click', async () => {
        const labeledFaceDescriptors = await getLabeledFaceDescriptions();
        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors);

        const canvas = faceapi.createCanvasFromMedia(video);
        document.getElementById('video-container').append(canvas);
        const displaySize = { width: video.width, height: video.height };
        faceapi.matchDimensions(canvas, displaySize);

        setInterval(async () => {
            const detections = await faceapi
                .detectAllFaces(video)
                .withFaceLandmarks()
                .withFaceDescriptors();

            const resizedDetections = faceapi.resizeResults(detections, displaySize);
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);

            const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));
            const recognizedChildren = results.map(result => result.label);

            // Mark attendance for recognized children
            recognizedChildren.forEach(childId => {
                markAttendance(childId);
            });

            // Draw bounding boxes and labels
            faceapi.draw.drawDetections(canvas, resizedDetections);
            faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
            results.forEach((result, i) => {
                const box = resizedDetections[i].detection.box;
                const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() });
                drawBox.draw(canvas);
            });
        }, 100);
    });

    // Mark attendance for a child
    // Mark attendance for a child
    function markAttendance(childId) {
    const workerId = <?php echo json_encode($worker_id); ?>;
    const centreId = <?php echo json_encode($centre_id); ?>;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'mark_attendance.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            console.log("Raw Response:", xhr.responseText); // Log the raw response
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        showMessage(`Attendance marked for child ID: ${childId}`);
                    } else {
                        showMessage(`Error: ${response.message}`);
                    }
                } catch (e) {
                    console.error("Failed to parse JSON:", e);
                    showMessage("Error: Invalid response from the server.");
                }
            } else {
                showMessage(`Error: HTTP Status ${xhr.status}`);
            }
        }
    };
    xhr.send(
        `child_id=${encodeURIComponent(childId)}&` +
        `worker_id=${encodeURIComponent(workerId)}&` +
        `centre_id=${encodeURIComponent(centreId)}`
    );
}

    // Show messages
    function showMessage(message) {
        messageDiv.textContent = message;
        messageDiv.style.display = 'block';
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 5000);
    }

    // End attendance
    endAttendanceButton.addEventListener('click', () => {
        stopWebcam();
        showMessage('Attendance session ended.');
    });
</script>
</body>
</html>