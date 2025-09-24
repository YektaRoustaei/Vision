# Laravel Vision

Free, open-source toolkit for real-time image and video processing for the Laravel framework. Includes basic image/video utilities and a native, extensible computer vision API for detection, faces, motion tracking, and AR — without requiring OpenCV.

## Vision Features

- Image processing (GD or Imagick)
  - Load and save JPEG/PNG/GIF
  - Resize (with aspect ratio), crop, rotate, watermark
  - Chainable helper (LaravelVision\Image\Vision): grayscale, blur, brightness/contrast, custom convolution kernels
- Video processing (FFmpeg)
  - Extract frames at specific timestamps
  - Simple transcoding with codec/preset/CRF options
- Advanced computer vision (Native driver by default)
  - Object detection: heuristic color/shape-based bounding boxes with scores
  - Face detection: basic skin-tone heuristic returning face-like regions
  - Motion tracking: ffmpeg-based frame extraction + frame differencing into motion regions with confidence
  - Augmented Reality: pose estimation (simple heuristic) and marker overlay API stub
- Laravel integration
  - Facade: Vision::images(), Vision::videos(), Vision::advanced()
  - Config publishing and swappable drivers via config/vision.php
- Extensible architecture
  - Contracts and drivers for Image, Video, and Advanced (Native driver only; no external LLM/ML dependencies)
- Configurable performance
  - Motion tracking fps and scale are configurable (VISION_MOTION_FPS, VISION_MOTION_SCALE)

## Languages and Technologies

- Primary language: PHP (Laravel package/library)
- Image backends: GD (PHP extension written in C) and Imagick (PHP extension binding to ImageMagick in C/C++)
- Video tooling: FFmpeg CLI (external tool written in C), invoked from PHP
- Advanced CV: Heuristic implementations in PHP only (no external LLM/ML dependencies)
- No JavaScript required; server-side only

## Installation

```bash
composer require laravel-vision/laravel-vision
```

Publish config:

```bash
php artisan vendor:publish --tag=vision-config
```

## Configuration

`config/vision.php`:

- image.driver: `gd` or `imagick`
- video.driver: `ffmpeg`
- advanced.driver: `native`

## How to Try (Step-by-step)

- Prerequisites
  - PHP 8.1+
  - GD (ext-gd) or Imagick (ext-imagick) for image functions
  - FFmpeg (ffmpeg, ffprobe in PATH) for video and motion tracking

Option A) Inside a Laravel app

1) Install the package

```bash
composer require laravel-vision/laravel-vision
```

2) Publish and review config

```bash
php artisan vendor:publish --tag=vision-config
```

- Set driver/env as needed in .env:
  - VISION_IMAGE_DRIVER=gd (or imagick)
  - VISION_VIDEO_DRIVER=ffmpeg
  - VISION_ADVANCED_DRIVER=native
  - Optional motion tuning: VISION_MOTION_FPS=2, VISION_MOTION_SCALE=320

3) Try it quickly via Tinker

```bash
php artisan tinker
>>> use LaravelVision\Facades\Vision;
// Image resize
>>> $img = Vision::images()->read(storage_path('app/example.jpg'));
>>> $res = Vision::images()->resize($img, 640, 640);
>>> Vision::images()->write($res, storage_path('app/out.jpg'));

// Object and face detection
>>> $objects = Vision::advanced()->objectDetector()->detect(storage_path('app/out.jpg'));
>>> $faces = Vision::advanced()->faceRecognizer()->detectFaces(storage_path('app/out.jpg'));
>>> $objects; $faces;

// Motion tracking on a short video
>>> $iter = Vision::advanced()->motionTracker()->track(storage_path('app/video.mp4'));
>>> foreach ($iter as $i => $regions) { echo "frame=$i regions=".count($regions)."\n"; }
```

4) Or wire up a quick route

```php
// routes/web.php
use Illuminate\Support\Facades\Storage;
use LaravelVision\Facades\Vision;

Route::get('/vision-demo', function () {
    $src = storage_path('app/example.jpg');
    $img = Vision::images()->read($src);
    $resized = Vision::images()->resize($img, 640, 640);
    $tmp = storage_path('app/out.jpg');
    Vision::images()->write($resized, $tmp);

    $objects = Vision::advanced()->objectDetector()->detect($tmp);
    return response()->json(['saved' => $tmp, 'objects' => $objects]);
});
```

Option B) Standalone (no Laravel) for chainable image processing

```php
// try.php
require __DIR__.'/vendor/autoload.php';
use LaravelVision\Image\Vision as Img;

Img::open(__DIR__.'/input.jpg')
    ->grayscale()
    ->blur(2)
    ->brightnessContrast(10, -15)
    ->convolve([
        [0, -1, 0],
        [-1, 5, -1],
        [0, -1, 0],
    ])
    ->save(__DIR__.'/output.jpg', 92);

echo "Saved to output.jpg\n";
```

Run it:

```bash
php try.php
```

Notes
- Put sample files at storage/app/example.jpg and storage/app/video.mp4 (Laravel) or project root for the standalone script.
- If FFmpeg isn’t found, install it and ensure `ffmpeg`/`ffprobe` are on PATH or set VISION_FFMPEG_BINARY and VISION_FFPROBE_BINARY.

## Quickstart

```php
use LaravelVision\Facades\Vision;

// Images
$img = Vision::images()->read(storage_path('app/example.jpg'));
$resized = Vision::images()->resize($img, 640, 640);
Vision::images()->write($resized, storage_path('app/out.jpg'));

// Video frame extraction
Vision::videos()->extractFrame(storage_path('app/video.mp4'), 1.5, storage_path('app/frame.jpg'));

// Advanced
$objects = Vision::advanced()->objectDetector()->detect(storage_path('app/out.jpg'));
$faces = Vision::advanced()->faceRecognizer()->detectFaces(storage_path('app/out.jpg'));

// Motion tracking (returns iterable of frameIdx => [regions])
$motion = Vision::advanced()->motionTracker()->track(storage_path('app/video.mp4'));
foreach ($motion as $frame => $regions) {
    foreach ($regions as $region) {
        // $region = ['bbox' => [x1,y1,x2,y2], 'score' => float]
    }
}
```

## Drivers

- Image: GD (default), Imagick
- Video: FFMpeg (shell)
- Advanced: Native driver only.

## Contributing

PRs welcome! See CONTRIBUTING.md.

## License

MIT


## Standalone chainable Vision image processing

The package also includes a simple, chainable image processing helper that works with either GD or Imagick (if installed).

Example:

```php
use LaravelVision\Image\Vision;

Vision::open(__DIR__ . '/input.jpg')
    ->grayscale()
    ->blur(2)               // increase to make blur stronger
    ->brightnessContrast(10, -15) // brighten a bit, reduce contrast a bit
    ->convolve([
        [0, -1, 0],
        [-1, 5, -1],
        [0, -1, 0],
    ]) // sharpening kernel
    ->save(__DIR__ . '/output.jpg', 92);
```
