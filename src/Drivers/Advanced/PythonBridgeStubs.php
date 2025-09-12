<?php

namespace LaravelVision\Drivers\Advanced;

use LaravelVision\Contracts\ObjectDetector;
use LaravelVision\Contracts\FaceRecognizer;
use LaravelVision\Contracts\MotionTracker;
use LaravelVision\Contracts\AugmentedReality;

class PythonBridgeStubs implements ObjectDetector, FaceRecognizer, MotionTracker, AugmentedReality
{
    public function detect(mixed $imageOrPath): array
    {
        return [];
    }

    public function detectFaces(mixed $imageOrPath): array
    {
        return [];
    }

    public function encodeFace(mixed $imageOrPath, array $bbox): string
    {
        return '';
    }

    public function compareEmbeddings(string $embeddingA, string $embeddingB): float
    {
        return 0.0;
    }

    public function track(string $videoPath): iterable
    {
        if (false) {
            yield [];
        }
        return [];
    }

    public function overlayOnMarker(string $videoPath, string $markerImagePath, string $overlayImagePath, string $outputPath): bool
    {
        return false;
    }

    public function estimatePose(mixed $imageOrPath): array
    {
        return [];
    }
}


