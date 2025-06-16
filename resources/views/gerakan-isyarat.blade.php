<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Hand Gesture Detection</title>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs-core"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs-backend-cpu"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs-tflite/dist/tf-tflite.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Roboto, sans-serif;
            background: #f5f5f5;
            color: #333;
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 0;
            overflow: hidden;
        }

        .header {
            background: #4285f4;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 1.2rem;
        }

        #container {
            position: relative;
            width: 100%;
            height: 70vh;
            /* 70% dari tinggi viewport */
            overflow: hidden;
            background: black;
            margin: 0 auto;
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: rotateY(180deg);
            -webkit-transform: rotateY(180deg);
        }

        canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transform: rotateY(180deg);
            -webkit-transform: rotateY(180deg);
        }

        .controls {
            padding: 15px;
            background: white;
            display: flex;
            flex-direction: column;
            gap: 10px;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            height: calc(30vh - 30px);
            /* Sisa tinggi untuk controls */
        }

        .loading {
            text-align: center;
            padding: 10px;
            color: #666;
        }

        .result {
            text-align: center;
            padding: 10px;
            font-size: 1.2rem;
            font-weight: bold;
            background: #f0f0f0;
            border-radius: 5px;
        }

        #gestureName {
            color: #4285f4;
        }

        @media (orientation: landscape) {
            body {
                flex-direction: row;
            }

            .header {
                display: none;
            }

            #container {
                width: 70%;
                height: 100vh;
            }

            .controls {
                width: 30%;
                height: 100vh;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div id="container">
        <video id="webcam" autoplay playsinline></video>
        <canvas id="output_canvas"></canvas>
    </div>

    <div class="controls">
        <div class="loading" id="statusText">Loading models...</div>
        <div class="result">
            <strong>Gesture:</strong> <span id="gestureName">-</span>
        </div>
    </div>

    <script type="module">
        let url = "{{ url('') }}";
        import {
            HandLandmarker,
            FilesetResolver
        } from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0";

        // Elements
        const video = document.getElementById("webcam");
        const canvas = document.getElementById("output_canvas");
        const ctx = canvas.getContext("2d");
        const gestureText = document.getElementById("gestureName");
        const statusText = document.getElementById("statusText");

        // Variables
        let handLandmarker, gestureModel, gestureLabels = [];
        let runningMode = "VIDEO";
        let webcamRunning = false;
        let videoAspectRatio = 1;

        // Update status text
        function updateStatus(message) {
            statusText.textContent = message;
        }

        // Initialize models and camera
        async function init() {
            try {
                updateStatus("Loading hand landmark model...");

                // Load MediaPipe model
                const vision = await FilesetResolver.forVisionTasks(
                    "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0/wasm"
                );

                handLandmarker = await HandLandmarker.createFromOptions(vision, {
                    baseOptions: {
                        modelAssetPath: "https://storage.googleapis.com/mediapipe-models/hand_landmarker/hand_landmarker/float16/1/hand_landmarker.task",
                        delegate: "GPU"
                    },
                    runningMode,
                    numHands: 2
                });

                updateStatus("Loading gesture model...");

                // Load gesture classification model & labels
                gestureModel = await tflite.loadTFLiteModel(
                    url + '/assets/models/hand_landmark_model.tflite');
                const labelRes = await fetch(url + '/assets/models/labels.txt');
                gestureLabels = (await labelRes.text()).trim().split('\n');

                updateStatus("Starting camera...");

                // Start camera automatically
                await enableCam();

                updateStatus("Ready");
            } catch (error) {
                console.error("Initialization error:", error);
                updateStatus("Error: " + error.message);
            }
        }

        // Predict gesture from landmarks
        async function predictGesture(landmarks) {
            try {
                const input = tf.tensor(landmarks, [1, 21, 3]);
                const output = gestureModel.predict(input);
                const data = await output.data();
                const maxIdx = data.indexOf(Math.max(...data));
                gestureText.innerText = gestureLabels[maxIdx] ?? "Unknown";

                input.dispose();
                output.dispose();
            } catch (error) {
                console.error("Prediction error:", error);
            }
        }

        // Webcam logic
        const enableCam = async () => {
            try {
                const constraints = {
                    video: {
                        facingMode: 'user',
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    }
                };

                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = stream;

                return new Promise((resolve) => {
                    video.onloadedmetadata = () => {
                        // Calculate aspect ratio
                        videoAspectRatio = video.videoWidth / video.videoHeight;

                        // Set canvas size to match video aspect ratio
                        resizeCanvas();

                        webcamRunning = true;
                        window.requestAnimationFrame(predictWebcam);
                        resolve();
                    };
                });
            } catch (err) {
                console.error("Camera access error:", err);
                updateStatus("Camera error: " + err.message);
                throw err;
            }
        };

        // Resize canvas to maintain correct aspect ratio
        function resizeCanvas() {
            const container = document.getElementById('container');
            const containerWidth = container.clientWidth;
            const containerHeight = container.clientHeight;

            let canvasWidth = containerWidth;
            let canvasHeight = containerHeight;

            // Adjust dimensions to maintain video aspect ratio
            const containerAspectRatio = containerWidth / containerHeight;

            if (containerAspectRatio > videoAspectRatio) {
                // Container is wider than video
                canvasWidth = containerHeight * videoAspectRatio;
            } else {
                // Container is taller than video
                canvasHeight = containerWidth / videoAspectRatio;
            }

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Center the canvas
            canvas.style.width = `${canvasWidth}px`;
            canvas.style.height = `${canvasHeight}px`;
            canvas.style.left = `${(containerWidth - canvasWidth) / 2}px`;
            canvas.style.top = `${(containerHeight - canvasHeight) / 2}px`;
        }

        // Detection loop with precise landmark positioning
        async function predictWebcam() {
            if (!webcamRunning) return;

            try {
                const results = await handLandmarker.detectForVideo(video, performance.now());

                // Clear canvas with the exact video dimensions
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                if (results.landmarks && results.landmarks.length > 0) {
                    for (const landmarks of results.landmarks) {
                        // Scale landmarks to canvas coordinates
                        const scaledLandmarks = landmarks.map(landmark => ({
                            x: landmark.x * canvas.width,
                            y: landmark.y * canvas.height,
                            z: landmark.z
                        }));

                        // Draw with precise coordinates
                        drawConnectors(ctx, scaledLandmarks, HAND_CONNECTIONS, {
                            color: "#00FF00",
                            lineWidth: 4
                        });
                        drawLandmarks(ctx, scaledLandmarks, {
                            color: "#FF0000",
                            lineWidth: 2
                        });

                        const flat = scaledLandmarks.flatMap(p => [p.x / canvas.width, p.y / canvas.height, p.z]);
                        await predictGesture(flat);
                    }
                } else {
                    gestureText.innerText = "-";
                }
            } catch (error) {
                console.error("Detection error:", error);
            }

            window.requestAnimationFrame(predictWebcam);
        }

        // Handle orientation and resize changes
        window.addEventListener('resize', () => {
            if (webcamRunning) {
                resizeCanvas();
            }
        });

        // Initialize everything when page loads
        window.addEventListener('DOMContentLoaded', init);
    </script>
</body>

</html>
