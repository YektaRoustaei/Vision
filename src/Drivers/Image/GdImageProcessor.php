<?php

namespace LaravelVision\Drivers\Image;

use LaravelVision\Contracts\ImageProcessor;
use RuntimeException;

class GdImageProcessor implements ImageProcessor
{
    protected function ensureGd(): void
    {
        if (!function_exists('imagecreatetruecolor')) {
            throw new RuntimeException('GD extension is required for GdImageProcessor');
        }
    }

    public function read(string $path): mixed
    {
        $this->ensureGd();
        if (!is_file($path)) {
            throw new RuntimeException("Image not found: {$path}");
        }
        $info = getimagesize($path);
        if (!$info) {
            throw new RuntimeException('Unsupported image type');
        }
        return match ($info[2]) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($path),
            IMAGETYPE_PNG => imagecreatefrompng($path),
            IMAGETYPE_GIF => imagecreatefromgif($path),
            default => throw new RuntimeException('Unsupported image type'),
        };
    }

    public function write(mixed $image, string $path, int $quality = 90): bool
    {
        $this->ensureGd();
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return match ($ext) {
            'jpg', 'jpeg' => imagejpeg($image, $path, $quality),
            'png' => imagepng($image, $path, (int) round((100 - $quality) / 10)),
            'gif' => imagegif($image, $path),
            default => throw new RuntimeException('Unsupported output format'),
        };
    }

    public function resize(mixed $image, int $width, int $height, bool $preserveAspect = true): mixed
    {
        $this->ensureGd();
        $srcWidth = imagesx($image);
        $srcHeight = imagesy($image);

        if ($preserveAspect) {
            $ratio = min($width / $srcWidth, $height / $srcHeight);
            $width = (int) max(1, floor($srcWidth * $ratio));
            $height = (int) max(1, floor($srcHeight * $ratio));
        }

        $dst = imagecreatetruecolor($width, $height);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $image, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
        return $dst;
    }

    public function crop(mixed $image, int $x, int $y, int $width, int $height): mixed
    {
        $this->ensureGd();
        $dst = imagecreatetruecolor($width, $height);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopy($dst, $image, 0, 0, $x, $y, $width, $height);
        return $dst;
    }

    public function rotate(mixed $image, float $degrees, string $bgColor = '#00000000'): mixed
    {
        $this->ensureGd();
        $rgba = $this->hexToRgba($bgColor);
        $color = imagecolorallocatealpha($image, $rgba[0], $rgba[1], $rgba[2], $rgba[3]);
        return imagerotate($image, -$degrees, $color);
    }

    public function watermark(mixed $image, mixed $watermark, int $x, int $y, float $opacity = 1.0): mixed
    {
        $this->ensureGd();
        $w = imagesx($watermark);
        $h = imagesy($watermark);
        imagecopymerge($image, $watermark, $x, $y, 0, 0, $w, $h, (int) round($opacity * 100));
        return $image;
    }

    protected function hexToRgba(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 8) {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            $a = 127 - (int) round(hexdec(substr($hex, 6, 2)) / 255 * 127);
            return [$r, $g, $b, $a];
        }
        if (strlen($hex) === 6) {
            return [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2)), 0];
        }
        return [0, 0, 0, 0];
    }
}


