<?php

namespace LaravelVision\Drivers\Image;

use LaravelVision\Contracts\ImageProcessor;
use RuntimeException;

class ImagickImageProcessor implements ImageProcessor
{
    public function __construct()
    {
        if (!class_exists(\Imagick::class)) {
            throw new RuntimeException('Imagick extension is not available');
        }
    }

    public function read(string $path): mixed
    {
        $img = new \Imagick($path);
        return $img;
    }

    public function write(mixed $image, string $path, int $quality = 90): bool
    {
        $image->setImageCompressionQuality($quality);
        return $image->writeImage($path);
    }

    public function resize(mixed $image, int $width, int $height, bool $preserveAspect = true): mixed
    {
        if ($preserveAspect) {
            $image->thumbnailImage($width, $height, true);
            return $image;
        }
        $image->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1);
        return $image;
    }

    public function crop(mixed $image, int $x, int $y, int $width, int $height): mixed
    {
        $image->cropImage($width, $height, $x, $y);
        return $image;
    }

    public function rotate(mixed $image, float $degrees, string $bgColor = '#00000000'): mixed
    {
        $image->rotateImage(new \ImagickPixel($bgColor), $degrees);
        return $image;
    }

    public function watermark(mixed $image, mixed $watermark, int $x, int $y, float $opacity = 1.0): mixed
    {
        if ($watermark instanceof \Imagick) {
            $watermark->setImageOpacity($opacity);
            $image->compositeImage($watermark, \Imagick::COMPOSITE_OVER, $x, $y);
            return $image;
        }
        throw new RuntimeException('Watermark must be an Imagick instance');
    }
}


