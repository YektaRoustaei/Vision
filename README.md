# Laravel Vision

Free, open-source toolkit for real-time image and video processing for the Laravel framework. Includes basic image/video utilities and a native, extensible computer vision API for detection, faces, motion tracking, and AR — without requiring OpenCV.

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
- advanced.driver: `native` or `python`

## Quickstart

```php
use LaravelVision\Facades\Vision;

// Images
$img = Vision::images()->read(storage_path('app/example.jpg'));
$resized = Vision::images()->resize($img, 640, 640);
Vision::images()->write($resized, storage_path('app/out.jpg'));

// Video frame extraction
Vision::videos()->extractFrame(storage_path('app/video.mp4'), 1.5, storage_path('app/frame.jpg'));

// Advanced (native stubs)
$objects = Vision::advanced()->objectDetector()->detect(storage_path('app/out.jpg'));
$faces = Vision::advanced()->faceRecognizer()->detectFaces(storage_path('app/out.jpg'));
```

## Drivers

- Image: GD (default), Imagick
- Video: FFMpeg (shell)
- Advanced: Native driver (default). Python bridge optional.

## Contributing

PRs welcome! See CONTRIBUTING.md.

## License

MIT
