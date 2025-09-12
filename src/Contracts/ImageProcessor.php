<?php

namespace LaravelVision\Contracts;

interface ImageProcessor
{
    public function read(string $path): mixed;
    public function write(mixed $image, string $path, int $quality = 90): bool;
    public function resize(mixed $image, int $width, int $height, bool $preserveAspect = true): mixed;
    public function crop(mixed $image, int $x, int $y, int $width, int $height): mixed;
    public function rotate(mixed $image, float $degrees, string $bgColor = '#00000000'): mixed;
    public function watermark(mixed $image, mixed $watermark, int $x, int $y, float $opacity = 1.0): mixed;
}


