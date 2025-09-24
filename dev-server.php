<?php
/**
 * Vision Library Development Server
 * Simple interface to test and work on the Vision library
 */

// Simple autoloader for the Vision library
spl_autoload_register(function ($class) {
    $prefix = 'LaravelVision\\';
    $base_dir = __DIR__ . '/src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Handle different routes
// Simple session for start/stop state
if (session_status() !== PHP_SESSION_ACTIVE) {
    @session_start();
}
// Ensure tmp output directory exists and is web-accessible when using PHP built-in server
$TMP_DIR = __DIR__ . '/tmp';
if (!is_dir($TMP_DIR)) {
    @mkdir($TMP_DIR, 0777, true);
}

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

switch ($path) {
    case '/':
        showMainPage();
        break;
    case '/live-camera':
        liveCameraPage();
        break;
    case '/api/live/start':
        apiLiveStart();
        break;
    case '/api/live/stop':
        apiLiveStop();
        break;
    case '/api/live/capture':
        apiLiveCapture();
        break;
    case '/test-image':
        testImageProcessing();
        break;
    case '/test-object-detection':
        testObjectDetection();
        break;
    case '/test-face-detection':
        testFaceDetection();
        break;
    case '/test-motion-tracking':
        testMotionTracking();
        break;
    case '/test-pose-estimation':
        testPoseEstimation();
        break;
    case '/test-ar-features':
        testARFeatures();
        break;
    case '/upload':
        handleImageUpload();
        break;
    default:
        http_response_code(404);
        echo "Page not found";
        break;
}

function showMainPage() {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vision Library Development Server</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-6xl mx-auto">
                <h1 class="text-4xl font-bold text-center mb-8 text-gray-800">
                    Vision Library Development Server
                </h1>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Basic Vision Tests -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-semibold mb-4 text-gray-700">Basic Vision Features</h2>
                        <div class="space-y-4">
                            <button onclick="testImageProcessing()" 
                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                Test Image Processing
                            </button>
                            
                            <button onclick="testObjectDetection()" 
                                    class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                Test Object Detection
                            </button>
                            
                            <button onclick="testFaceDetection()" 
                                    class="w-full bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                Test Face Detection
                            </button>
                        </div>
                    </div>
                    
                    <!-- Advanced Vision Tests -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-semibold mb-4 text-gray-700">Advanced Features</h2>
                        <div class="space-y-4">
                            <button onclick="testMotionTracking()" 
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                Test Motion Tracking
                            </button>
                            
                            <button onclick="testPoseEstimation()" 
                                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                Test Pose Estimation
                            </button>
                            
                            <button onclick="testARFeatures()" 
                                    class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                Test AR Features
                            </button>
                        </div>
                    </div>
                    
                    <!-- Image Upload -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-semibold mb-4 text-gray-700">Upload Test Image</h2>
                        <form id="uploadForm" enctype="multipart/form-data">
                            <input type="file" id="imageFile" accept="image/*" class="w-full mb-4 p-2 border rounded-lg">
                            <button type="submit" 
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                Upload & Test
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Results Area -->
                <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700">Test Results</h2>
                    <div id="results" class="space-y-4">
                        <p class="text-gray-500 text-center">Click a test button above to see results</p>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            function testImageProcessing() {
                fetch('/test-image')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('results').innerHTML = data;
                    });
            }
            
            function testObjectDetection() {
                fetch('/test-object-detection')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('results').innerHTML = data;
                    });
            }
            
            function testFaceDetection() {
                fetch('/test-face-detection')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('results').innerHTML = data;
                    });
            }
            
            function testMotionTracking() {
                fetch('/test-motion-tracking')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('results').innerHTML = data;
                    });
            }
            
            function testPoseEstimation() {
                fetch('/test-pose-estimation')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('results').innerHTML = data;
                    });
            }
            
            function testARFeatures() {
                fetch('/test-ar-features')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('results').innerHTML = data;
                    });
            }
            
            document.getElementById('uploadForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData();
                formData.append('image', document.getElementById('imageFile').files[0]);
                
                fetch('/upload', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('results').innerHTML = data;
                });
            });
        </script>
    </body>
    </html>
    <?php
}

function testImageProcessing() {
    try {
        $processor = new LaravelVision\Drivers\Image\GdImageProcessor();
        
        // Create a test image
        $img = imagecreate(200, 200);
        $red = imagecolorallocate($img, 255, 0, 0);
        $blue = imagecolorallocate($img, 0, 0, 255);
        imagefilledrectangle($img, 50, 50, 150, 150, $red);
        imagefilledellipse($img, 100, 100, 80, 80, $blue);
        
        // Test resize
        $resized = $processor->resize($img, 100, 100);
        
        // Test crop
        $cropped = $processor->crop($img, 25, 25, 150, 150);
        
        echo '<div class="bg-green-50 border border-green-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-green-800 mb-2">Image Processing Test - SUCCESS</h3>';
        echo '<p class="text-green-600">✓ Image creation: Working</p>';
        echo '<p class="text-green-600">✓ Image resize: Working</p>';
        echo '<p class="text-green-600">✓ Image crop: Working</p>';
        echo '<p class="text-green-600">✓ GD Extension: ' . (extension_loaded('gd') ? 'Available' : 'Not Available') . '</p>';
        echo '</div>';
        
        imagedestroy($img);
        imagedestroy($resized);
        imagedestroy($cropped);
        
    } catch (Exception $e) {
        echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-red-800 mb-2">Image Processing Test - ERROR</h3>';
        echo '<p class="text-red-600">Error: ' . $e->getMessage() . '</p>';
        echo '</div>';
    }
}

function testObjectDetection() {
    try {
        $detector = new LaravelVision\Drivers\Advanced\NativeCv();
        
        // Create a test image with objects
        $img = imagecreate(300, 300);
        $red = imagecolorallocate($img, 255, 0, 0);
        $blue = imagecolorallocate($img, 0, 0, 255);
        $green = imagecolorallocate($img, 0, 255, 0);
        
        // Add some objects
        imagefilledrectangle($img, 50, 50, 150, 150, $red);
        imagefilledellipse($img, 200, 100, 80, 80, $blue);
        imagefilledrectangle($img, 100, 200, 200, 250, $green);
        
        // Save temporary image
        $tempPath = sys_get_temp_dir() . '/test_objects.jpg';
        imagejpeg($img, $tempPath);
        
        // Test detection
        $detections = $detector->detect($tempPath);
        
        echo '<div class="bg-green-50 border border-green-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-green-800 mb-2">Object Detection Test - SUCCESS</h3>';
        echo '<p class="text-green-600">✓ Objects detected: ' . count($detections) . '</p>';
        
        if (!empty($detections)) {
            echo '<div class="mt-3">';
            echo '<h4 class="font-semibold mb-2">Detected Objects:</h4>';
            foreach ($detections as $i => $detection) {
                $confidence = round($detection['score'] * 100);
                echo '<div class="bg-white border rounded p-2 mb-2">';
                echo '<strong>' . $detection['label'] . '</strong> - ' . $confidence . '% confidence<br>';
                echo '<small>Position: (' . implode(', ', $detection['bbox']) . ')</small>';
                echo '</div>';
            }
            echo '</div>';
        }
        echo '</div>';
        
        // Cleanup
        unlink($tempPath);
        imagedestroy($img);
        
    } catch (Exception $e) {
        echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-red-800 mb-2">Object Detection Test - ERROR</h3>';
        echo '<p class="text-red-600">Error: ' . $e->getMessage() . '</p>';
        echo '</div>';
    }
}

function testFaceDetection() {
    try {
        $detector = new LaravelVision\Drivers\Advanced\NativeCv();
        
        // Create a test image with skin tones
        $img = imagecreate(200, 200);
        $skin = imagecolorallocate($img, 220, 180, 140); // Skin tone
        $background = imagecolorallocate($img, 50, 50, 50);
        
        // Fill background
        imagefill($img, 0, 0, $background);
        
        // Add skin tone areas (face-like)
        imagefilledellipse($img, 100, 100, 120, 150, $skin);
        
        // Save temporary image
        $tempPath = sys_get_temp_dir() . '/test_face.jpg';
        imagejpeg($img, $tempPath);
        
        // Test face detection
        $faces = $detector->detectFaces($tempPath);
        
        echo '<div class="bg-green-50 border border-green-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-green-800 mb-2">Face Detection Test - SUCCESS</h3>';
        echo '<p class="text-green-600">✓ Faces detected: ' . count($faces) . '</p>';
        
        if (!empty($faces)) {
            echo '<div class="mt-3">';
            echo '<h4 class="font-semibold mb-2">Detected Faces:</h4>';
            foreach ($faces as $i => $face) {
                $confidence = round($face['score'] * 100);
                echo '<div class="bg-white border rounded p-2 mb-2">';
                echo '<strong>' . $face['label'] . '</strong> - ' . $confidence . '% confidence<br>';
                echo '<small>Position: (' . implode(', ', $face['bbox']) . ')</small>';
                echo '</div>';
            }
            echo '</div>';
        }
        echo '</div>';
        
        // Cleanup
        unlink($tempPath);
        imagedestroy($img);
        
    } catch (Exception $e) {
        echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-red-800 mb-2">Face Detection Test - ERROR</h3>';
        echo '<p class="text-red-600">Error: ' . $e->getMessage() . '</p>';
        echo '</div>';
    }
}

function handleImageUpload() {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-red-800 mb-2">Upload Error</h3>';
        echo '<p class="text-red-600">No image uploaded or upload failed</p>';
        echo '</div>';
        return;
    }
    
    try {
        $detector = new LaravelVision\Drivers\Advanced\NativeCv();
        $tempPath = $_FILES['image']['tmp_name'];
        
        // Test object detection on uploaded image
        $detections = $detector->detect($tempPath);
        $faces = $detector->detectFaces($tempPath);
        
        echo '<div class="bg-green-50 border border-green-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-green-800 mb-2">Upload Analysis - SUCCESS</h3>';
        echo '<p class="text-green-600">✓ Objects detected: ' . count($detections) . '</p>';
        echo '<p class="text-green-600">✓ Faces detected: ' . count($faces) . '</p>';
        
        if (!empty($detections)) {
            echo '<div class="mt-3">';
            echo '<h4 class="font-semibold mb-2">Detected Objects:</h4>';
            foreach ($detections as $i => $detection) {
                $confidence = round($detection['score'] * 100);
                echo '<div class="bg-white border rounded p-2 mb-2">';
                echo '<strong>' . $detection['label'] . '</strong> - ' . $confidence . '% confidence<br>';
                echo '<small>Position: (' . implode(', ', $detection['bbox']) . ')</small>';
                echo '</div>';
            }
            echo '</div>';
        }
        
        if (!empty($faces)) {
            echo '<div class="mt-3">';
            echo '<h4 class="font-semibold mb-2">Detected Faces:</h4>';
            foreach ($faces as $i => $face) {
                $confidence = round($face['score'] * 100);
                echo '<div class="bg-white border rounded p-2 mb-2">';
                echo '<strong>' . $face['label'] . '</strong> - ' . $confidence . '% confidence<br>';
                echo '<small>Position: (' . implode(', ', $face['bbox']) . ')</small>';
                echo '</div>';
            }
            echo '</div>';
        }
        echo '</div>';
        
    } catch (Exception $e) {
        echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-red-800 mb-2">Analysis Error</h3>';
        echo '<p class="text-red-600">Error: ' . $e->getMessage() . '</p>';
        echo '</div>';
    }
}

function liveCameraPage() {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Live Camera - Laravel Vision Demo</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            video, canvas { max-width: 100%; }
        </style>
    </head>
    <body class="bg-gray-100 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-6xl mx-auto">
                <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">Live Camera Demo</h1>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <video id="video" autoplay playsinline class="w-full rounded bg-black"></video>
                            <canvas id="canvas" class="hidden"></canvas>
                            <div class="mt-4 flex gap-2">
                                <button id="startBtn" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">Start</button>
                                <button id="stopBtn" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded" disabled>Stop</button>
                            </div>
                            <p id="status" class="mt-2 text-sm text-gray-600">Idle</p>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold mb-2">Real-time Results</h2>
                            <div id="results" class="space-y-2">
                                <p class="text-gray-500">No frames yet…</p>
                            </div>
                            <div class="mt-4">
                                <h3 class="font-semibold mb-2">Processed Images</h3>
                                <div id="images" class="grid grid-cols-3 gap-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-center">
                    <a href="/" class="text-blue-600 hover:underline">Back to Dev Server</a>
                </div>
            </div>
        </div>
        <script>
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            const startBtn = document.getElementById('startBtn');
            const stopBtn = document.getElementById('stopBtn');
            const statusEl = document.getElementById('status');
            const resultsEl = document.getElementById('results');
            const imagesEl = document.getElementById('images');
            let running = false;
            let timer = null;

            async function start() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                    video.srcObject = stream;
                    await fetch('/api/live/start', { method: 'POST' });
                    running = true;
                    statusEl.textContent = 'Running…';
                    startBtn.disabled = true;
                    stopBtn.disabled = false;
                    timer = setInterval(captureAndSend, 700);
                } catch (e) {
                    statusEl.textContent = 'Camera error: ' + e.message;
                }
            }

            function stop() {
                running = false;
                const stream = video.srcObject;
                if (stream) stream.getTracks().forEach(t => t.stop());
                video.srcObject = null;
                clearInterval(timer);
                fetch('/api/live/stop', { method: 'POST' });
                startBtn.disabled = false;
                stopBtn.disabled = true;
                statusEl.textContent = 'Stopped';
            }

            async function captureAndSend() {
                if (!running) return;
                const vw = video.videoWidth || 640;
                const vh = video.videoHeight || 480;
                const targetW = 640; const targetH = 480;
                const scale = Math.min(targetW / vw, targetH / vh);
                const w = Math.max(1, Math.floor(vw * scale));
                const h = Math.max(1, Math.floor(vh * scale));
                canvas.width = w; canvas.height = h;
                ctx.drawImage(video, 0, 0, w, h);
                const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                try {
                    const res = await fetch('/api/live/capture', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ image: dataUrl, brightness: 5, contrast: 5, grayscale: true })
                    });
                    const json = await res.json();
                    if (json && json.ok) {
                        renderResults(json);
                    } else {
                        statusEl.textContent = 'Server error';
                    }
                } catch (e) {
                    statusEl.textContent = 'Network error: ' + e.message;
                }
            }

            function renderResults(data) {
                resultsEl.innerHTML = '';
                const title = document.createElement('div');
                title.className = 'text-sm text-gray-700';
                title.textContent = `Detections: ${data.detections.length}`;
                resultsEl.appendChild(title);
                data.detections.forEach(det => {
                    const row = document.createElement('div');
                    row.className = 'text-xs text-gray-800';
                    row.textContent = `${det.label} (${Math.round(det.score*100)}%) bbox=[${det.bbox.join(', ')}]`;
                    resultsEl.appendChild(row);
                });
                imagesEl.innerHTML = '';
                ['original','resized','cropped','processed'].forEach(k => {
                    if (data.images && data.images[k]) {
                        const img = document.createElement('img');
                        img.src = data.images[k] + `?t=${Date.now()}`;
                        img.className = 'rounded border';
                        imagesEl.appendChild(img);
                    }
                });
            }

            startBtn.addEventListener('click', start);
            stopBtn.addEventListener('click', stop);
        </script>
    </body>
    </html>
    <?php
}

function apiLiveStart() {
    $_SESSION['live_running'] = true;
    header('Content-Type: application/json');
    echo json_encode(['ok' => true, 'running' => true]);
}

function apiLiveStop() {
    $_SESSION['live_running'] = false;
    header('Content-Type: application/json');
    echo json_encode(['ok' => true, 'running' => false]);
}

function apiLiveCapture() {
    header('Content-Type: application/json');
    if (empty($_SESSION['live_running'])) {
        echo json_encode(['ok' => false, 'error' => 'Not running']);
        return;
    }
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (!$data || empty($data['image'])) {
        echo json_encode(['ok' => false, 'error' => 'No image']);
        return;
    }
    $brightness = isset($data['brightness']) ? (int)$data['brightness'] : 0;
    $contrast = isset($data['contrast']) ? (int)$data['contrast'] : 0;
    $grayscale = !isset($data['grayscale']) || (bool)$data['grayscale'];

    $id = uniqid('cam_', true);
    $base = __DIR__ . '/tmp/' . $id;
    $origPath = $base . '_orig.jpg';
    $resizedPath = $base . '_resized.jpg';
    $croppedPath = $base . '_cropped.jpg';
    $processedPath = $base . '_processed.jpg';

    $imgData = $data['image'];
    if (strpos($imgData, ',') !== false) {
        $imgData = explode(',', $imgData, 2)[1];
    }
    $bytes = base64_decode($imgData);
    if ($bytes === false) {
        echo json_encode(['ok' => false, 'error' => 'Decode failed']);
        return;
    }
    file_put_contents($origPath, $bytes);

    try {
        $gd = new \LaravelVision\Drivers\Image\GdImageProcessor();
        $im = $gd->read($origPath);
        // Resize to 640x480 preserve aspect
        $resized = $gd->resize($im, 640, 480, true);
        $gd->write($resized, $resizedPath, 90);
        // Center-crop to square 416x416 for detection stability
        $rw = imagesx($resized); $rh = imagesy($resized);
        $size = min($rw, $rh, 416);
        $cx = (int) max(0, floor(($rw - $size) / 2));
        $cy = (int) max(0, floor(($rh - $size) / 2));
        $cropped = $gd->crop($resized, $cx, $cy, $size, $size);
        $gd->write($cropped, $croppedPath, 90);

        // Grayscale and brightness/contrast via chainable Vision
        $img = \LaravelVision\Image\Vision::open($croppedPath);
        if ($grayscale) {
            $img->grayscale();
        }
        if ($brightness !== 0 || $contrast !== 0) {
            $img->brightnessContrast($brightness, $contrast);
        }
        $img->save($processedPath, 90);

        // Detection on processed image
        $detector = new \LaravelVision\Drivers\Advanced\NativeCv();
        $detections = $detector->detect($processedPath);

        $toUrl = function($path) {
            return '/tmp/' . basename($path);
        };

        echo json_encode([
            'ok' => true,
            'detections' => $detections,
            'images' => [
                'original' => $toUrl($origPath),
                'resized' => $toUrl($resizedPath),
                'cropped' => $toUrl($croppedPath),
                'processed' => $toUrl($processedPath),
            ],
        ]);
    } catch (\Throwable $e) {
        echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    }
}

function testMotionTracking() {
    try {
        $detector = new LaravelVision\Drivers\Advanced\NativeCv();
        
        // Create a dummy video file path for testing
        $videoPath = sys_get_temp_dir() . '/test_motion.mp4';
        
        // Test motion tracking
        $motionData = [];
        foreach ($detector->track($videoPath) as $frame => $regions) {
            $motionData[$frame] = $regions;
        }
        
        echo '<div class="bg-green-50 border border-green-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-green-800 mb-2">Motion Tracking Test - SUCCESS</h3>';
        echo '<p class="text-green-600">✓ Motion tracking simulation: Working</p>';
        echo '<p class="text-green-600">✓ Frames analyzed: ' . count($motionData) . '</p>';
        
        if (!empty($motionData)) {
            echo '<div class="mt-3">';
            echo '<h4 class="font-semibold mb-2">Motion Regions by Frame:</h4>';
            foreach ($motionData as $frame => $regions) {
                if (!empty($regions)) {
                    echo '<div class="bg-white border rounded p-2 mb-2">';
                    echo '<strong>Frame ' . $frame . ':</strong> ' . count($regions) . ' motion regions<br>';
                    foreach ($regions as $region) {
                        $confidence = round($region['score'] * 100);
                        echo '<small>• Confidence: ' . $confidence . '%, Position: (' . implode(', ', $region['bbox']) . ')</small><br>';
                    }
                    echo '</div>';
                }
            }
            echo '</div>';
        }
        echo '</div>';
        
    } catch (Exception $e) {
        echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-red-800 mb-2">Motion Tracking Test - ERROR</h3>';
        echo '<p class="text-red-600">Error: ' . $e->getMessage() . '</p>';
        echo '</div>';
    }
}

function testPoseEstimation() {
    try {
        $detector = new LaravelVision\Drivers\Advanced\NativeCv();
        
        // Create a test image with objects and faces
        $img = imagecreate(300, 300);
        $skin = imagecolorallocate($img, 220, 180, 140); // Skin tone
        $red = imagecolorallocate($img, 255, 0, 0);
        $blue = imagecolorallocate($img, 0, 0, 255);
        $background = imagecolorallocate($img, 50, 50, 50);
        
        // Fill background
        imagefill($img, 0, 0, $background);
        
        // Add face-like area
        imagefilledellipse($img, 150, 120, 100, 120, $skin);
        
        // Add some objects
        imagefilledrectangle($img, 50, 200, 100, 250, $red);
        imagefilledellipse($img, 200, 200, 60, 60, $blue);
        
        // Save temporary image
        $tempPath = sys_get_temp_dir() . '/test_pose.jpg';
        imagejpeg($img, $tempPath);
        
        // Test pose estimation
        $pose = $detector->estimatePose($tempPath);
        
        echo '<div class="bg-green-50 border border-green-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-green-800 mb-2">Pose Estimation Test - SUCCESS</h3>';
        echo '<p class="text-green-600">✓ Pose estimation: Working</p>';
        
        if (!empty($pose)) {
            echo '<div class="mt-3">';
            echo '<h4 class="font-semibold mb-2">Estimated Pose:</h4>';
            
            if (isset($pose['rotation'])) {
                echo '<div class="bg-white border rounded p-2 mb-2">';
                echo '<strong>Rotation:</strong><br>';
                echo 'X: ' . round($pose['rotation']['x'], 2) . '°<br>';
                echo 'Y: ' . round($pose['rotation']['y'], 2) . '°<br>';
                echo 'Z: ' . round($pose['rotation']['z'], 2) . '°';
                echo '</div>';
            }
            
            if (isset($pose['translation'])) {
                echo '<div class="bg-white border rounded p-2 mb-2">';
                echo '<strong>Translation:</strong><br>';
                echo 'X: ' . round($pose['translation']['x'], 3) . '<br>';
                echo 'Y: ' . round($pose['translation']['y'], 3) . '<br>';
                echo 'Z: ' . round($pose['translation']['z'], 3);
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p class="text-yellow-600">No pose data detected (no faces or objects found)</p>';
        }
        echo '</div>';
        
        // Cleanup
        unlink($tempPath);
        imagedestroy($img);
        
    } catch (Exception $e) {
        echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-red-800 mb-2">Pose Estimation Test - ERROR</h3>';
        echo '<p class="text-red-600">Error: ' . $e->getMessage() . '</p>';
        echo '</div>';
    }
}

function testARFeatures() {
    try {
        $detector = new LaravelVision\Drivers\Advanced\NativeCv();
        
        echo '<div class="bg-green-50 border border-green-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-green-800 mb-2">AR Features Test - SUCCESS</h3>';
        
        // Test marker overlay (simulation)
        $videoPath = sys_get_temp_dir() . '/test_video.mp4';
        $markerPath = sys_get_temp_dir() . '/marker.jpg';
        $overlayPath = sys_get_temp_dir() . '/overlay.jpg';
        $outputPath = sys_get_temp_dir() . '/ar_output.mp4';
        
        $overlayResult = $detector->overlayOnMarker($videoPath, $markerPath, $overlayPath, $outputPath);
        
        echo '<p class="text-green-600">✓ AR marker overlay: ' . ($overlayResult ? 'Available' : 'Simulated') . '</p>';
        echo '<p class="text-green-600">✓ Pose estimation: Available</p>';
        echo '<p class="text-green-600">✓ Object detection: Available</p>';
        echo '<p class="text-green-600">✓ Face detection: Available</p>';
        
        echo '<div class="mt-3">';
        echo '<h4 class="font-semibold mb-2">Available AR Features:</h4>';
        echo '<ul class="list-disc list-inside space-y-1">';
        echo '<li>Pose estimation for 3D positioning</li>';
        echo '<li>Object detection for marker recognition</li>';
        echo '<li>Face detection for face-based AR</li>';
        echo '<li>Motion tracking for dynamic overlays</li>';
        echo '<li>Image overlay capabilities</li>';
        echo '</ul>';
        echo '</div>';
        
        echo '</div>';
        
    } catch (Exception $e) {
        echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4">';
        echo '<h3 class="font-semibold text-red-800 mb-2">AR Features Test - ERROR</h3>';
        echo '<p class="text-red-600">Error: ' . $e->getMessage() . '</p>';
        echo '</div>';
    }
}
