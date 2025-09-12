<?php

namespace LaravelVision\Contracts;

interface AugmentedReality
{
    /**
     * Overlay a 2D image on detected marker and render to output.
     */
    public function overlayOnMarker(string $videoPath, string $markerImagePath, string $overlayImagePath, string $outputPath): bool;

    /**
     * Estimate pose from single image; returns rotation/translation vectors if available.
     * @return array{rotation?:array, translation?:array}
     */
    public function estimatePose(mixed $imageOrPath): array;
}


