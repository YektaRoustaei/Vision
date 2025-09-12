<?php

namespace LaravelVision\Contracts;

interface VideoProcessor
{
    public function open(string $path): mixed;
    public function save(mixed $video, string $path): bool;
    public function extractFrame(string $path, float $seconds, string $outputPath): bool;
    public function transcode(string $path, string $outputPath, array $options = []): bool;
}


