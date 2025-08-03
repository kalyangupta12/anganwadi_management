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
    <style>
        #video-container {
            position: relative;
            width: 640px;
            height: 480px;
        }

        #video {
            width: 100%;
            height: 100%;
        }

        #overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Mark Attendance</h1>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div id="video-container" class="mb-4">
                <video id="video" width="640" height="480" autoplay></video>
                <canvas id="overlay"></canvas>
            </div>
            <button id="startButton" class="bg-blue-500 text-white p-2 rounded">Start Webcam</button>
            <button id="switchButton" onclick="switchCamera()" class="bg-green-500 text-white p-2 rounded">Switch Camera</button>
            <button id="endAttendance" class="bg-red-500 text-white p-2 rounded">End Attendance</button>
        </div>
        <div id="messageDiv" class="mt-4"></div>
    </div>

    <script>
        // Configuration
        const FACE_DETECTION_CONFIDENCE = 0.6; // Slightly lower for speed
        const FACE_MATCHER_THRESHOLD = 0.5; // Balanced threshold
        const COOLDOWN_TIME = 5000; // 5 seconds cooldown per child
        const FRAME_SKIP = 2; // Process every 2nd frame
        const lastMarked = {}; // Track last marked time for each child

        // Load lightweight face-api.js models
        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('../../assets/models'),
            faceapi.nets.faceLandmark68TinyNet.loadFromUri('../../assets/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('../../assets/models')
        ]).then(startWebcam).catch(err => {
            console.error("Error loading models:", err);
            alert("Failed to load models. Please check the console for details.");
        });

        const video = document.getElementById('video');
        const startButton = document.getElementById('startButton');
        const switchButton = document.getElementById('switchButton');
        const endAttendanceButton = document.getElementById('endAttendance');
        const messageDiv = document.getElementById('messageDiv');
        let videoStream;
        let frameCount = 0;
        let currentCamera = 0; // Track the current camera

        // Start webcam with lower resolution and selected camera
        async function startWebcam() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === "videoinput");

                if (videoDevices.length === 0) {
                    alert("No video devices found.");
                    return;
                }

                const deviceId = videoDevices[currentCamera % videoDevices.length].deviceId;

                // Stop any previously running video stream
                if (videoStream) {
                    videoStream.getTracks().forEach(track => track.stop());
                }

                // Start the selected camera
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        deviceId: { exact: deviceId },
                        width: 640,
                        height: 480
                    },
                    audio: false
                });

                video.srcObject = stream;
                videoStream = stream;
            } catch (err) {
                console.error("Error accessing webcam:", err);
                alert("Failed to access webcam. Please ensure your camera is connected and permissions are granted.");
            }
        }

        // Switch between available cameras
        function switchCamera() {
            currentCamera++;
            startWebcam(); // Restart webcam with the next camera
        }

        // Stop webcam
        function stopWebcam() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }
        }

        // Precompute and cache face descriptors
        async function getLabeledFaceDescriptions() {
            const labeledFaceDescriptors = [];
            const children = <?php echo json_encode($children); ?>;

            await Promise.all(children.map(async (child) => {
                const { child_id, image_path } = child;
                const descriptions = [];

                try {
                    const img = await faceapi.fetchImage(image_path);
                    const detections = await faceapi
                        .detectSingleFace(
                            img,
                            new faceapi.TinyFaceDetectorOptions({ inputSize: 160, scoreThreshold: FACE_DETECTION_CONFIDENCE })
                        )
                        .withFaceLandmarks(true)
                        .withFaceDescriptor();

                    if (detections) {
                        descriptions.push(detections.descriptor);
                    }
                } catch (err) {
                    console.error(`Error processing ${image_path}:`, err);
                }

                if (descriptions.length > 0) {
                    labeledFaceDescriptors.push(
                        new faceapi.LabeledFaceDescriptors(child_id, descriptions)
                    );
                }
            }));

            return labeledFaceDescriptors;
        }

        // Start facial recognition
        startButton.addEventListener('click', async () => {
            const labeledFaceDescriptors = await getLabeledFaceDescriptions();
            const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, FACE_MATCHER_THRESHOLD);

            const canvas = document.getElementById('overlay');
            const context = canvas.getContext('2d');
            const displaySize = { width: video.videoWidth, height: video.videoHeight };
            faceapi.matchDimensions(canvas, displaySize);

            setInterval(async () => {
                frameCount++;
                if (frameCount % FRAME_SKIP !== 0) return; // Skip frames

                const detections = await faceapi
                    .detectAllFaces(
                        video,
                        new faceapi.TinyFaceDetectorOptions({ inputSize: 160, scoreThreshold: FACE_DETECTION_CONFIDENCE })
                    )
                    .withFaceLandmarks(true)
                    .withFaceDescriptors();

                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                context.clearRect(0, 0, canvas.width, canvas.height);

                const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));
                results.forEach((result, i) => {
                    const box = resizedDetections[i].detection.box;
                    if (result.label !== 'unknown' && result.distance < FACE_MATCHER_THRESHOLD) {
                        markAttendance(result.label);
                        context.strokeStyle = '#00FF00';
                    } else {
                        context.strokeStyle = '#FF0000';
                    }

                    context.lineWidth = 2;
                    context.strokeRect(box.x, box.y, box.width, box.height);
                    context.fillStyle = context.strokeStyle;
                    context.fillText(result.toString(), box.x + 6, box.y - 10);
                });
            }, 100); // Process every 100ms
        });

        // Mark attendance for a child
        function markAttendance(childId) {
            const now = Date.now();
            if (lastMarked[childId] && now - lastMarked[childId] < COOLDOWN_TIME) {
                return;
            }

            lastMarked[childId] = now;

            const workerId = <?php echo json_encode($worker_id); ?>;
            const centreId = <?php echo json_encode($centre_id); ?>;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'mark_attendance.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    showMessage(`Attendance marked for child ID: ${childId}`);
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
            }, 3000);
        }

        // End attendance session
        endAttendanceButton.addEventListener('click', () => {
            stopWebcam();
            showMessage('Attendance session ended.');
        });
    </script>
</body>

</html>