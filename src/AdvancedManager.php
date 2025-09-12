<?php

namespace LaravelVision;

use LaravelVision\Contracts\ObjectDetector;
use LaravelVision\Contracts\FaceRecognizer;
use LaravelVision\Contracts\MotionTracker;
use LaravelVision\Contracts\AugmentedReality;

class AdvancedManager
{
    protected ?ObjectDetector $detector = null;
    protected ?FaceRecognizer $faces = null;
    protected ?MotionTracker $motion = null;
    protected ?AugmentedReality $ar = null;

    public function objectDetector(): ObjectDetector
    {
        if ($this->detector) return $this->detector;
        $driver = config('vision.advanced.driver', 'native');
        return $this->detector = $this->buildDriver($driver);
    }

    public function faceRecognizer(): FaceRecognizer
    {
        if ($this->faces) return $this->faces;
        $driver = config('vision.advanced.driver', 'native');
        /** @var FaceRecognizer */
        return $this->faces = $this->buildDriver($driver);
    }

    public function motionTracker(): MotionTracker
    {
        if ($this->motion) return $this->motion;
        $driver = config('vision.advanced.driver', 'native');
        /** @var MotionTracker */
        return $this->motion = $this->buildDriver($driver);
    }

    public function augmentedReality(): AugmentedReality
    {
        if ($this->ar) return $this->ar;
        $driver = config('vision.advanced.driver', 'native');
        /** @var AugmentedReality */
        return $this->ar = $this->buildDriver($driver);
    }

    protected function buildDriver(string $driver): mixed
    {
        return match ($driver) {
            'python' => new \LaravelVision\Drivers\Advanced\PythonBridgeStubs(),
            'native' => new \LaravelVision\Drivers\Advanced\NativeCv(),
            default => new \LaravelVision\Drivers\Advanced\NativeCv(),
        };
    }
}


