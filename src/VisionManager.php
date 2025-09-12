<?php

namespace LaravelVision;

use Illuminate\Contracts\Container\Container;
use LaravelVision\Contracts\ImageProcessor;
use LaravelVision\Contracts\VideoProcessor;

class VisionManager
{
    protected Container $container;

    protected ?ImageProcessor $imageProcessor = null;
    protected ?VideoProcessor $videoProcessor = null;
    protected ?AdvancedManager $advanced = null;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function images(): ImageProcessor
    {
        if ($this->imageProcessor) {
            return $this->imageProcessor;
        }

        $driver = config('vision.image.driver', 'gd');

        return $this->imageProcessor = match ($driver) {
            'gd' => new \LaravelVision\Drivers\Image\GdImageProcessor(),
            'imagick' => class_exists('Imagick')
                ? new \LaravelVision\Drivers\Image\ImagickImageProcessor()
                : new \LaravelVision\Drivers\Image\GdImageProcessor(),
            default => new \LaravelVision\Drivers\Image\GdImageProcessor(),
        };
    }

    public function videos(): VideoProcessor
    {
        if ($this->videoProcessor) {
            return $this->videoProcessor;
        }

        $driver = config('vision.video.driver', 'ffmpeg');

        return $this->videoProcessor = match ($driver) {
            'ffmpeg' => new \LaravelVision\Drivers\Video\FfmpegVideoProcessor(),
            default => new \LaravelVision\Drivers\Video\FfmpegVideoProcessor(),
        };
    }

    public function advanced(): AdvancedManager
    {
        if ($this->advanced) {
            return $this->advanced;
        }
        return $this->advanced = new AdvancedManager();
    }
}


