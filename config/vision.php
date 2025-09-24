<?php

return [
    'image' => [
        'driver' => env('VISION_IMAGE_DRIVER', 'gd'), // gd|imagick
        'default_quality' => 90,
    ],

    'video' => [
        'driver' => env('VISION_VIDEO_DRIVER', 'ffmpeg'), // ffmpeg
        'ffmpeg' => [
            'binary' => env('VISION_FFMPEG_BINARY', 'ffmpeg'),
            'ffprobe_binary' => env('VISION_FFPROBE_BINARY', 'ffprobe'),
            'timeout' => env('VISION_FFMPEG_TIMEOUT', 60),
        ],
    ],

    'advanced' => [
        'driver' => env('VISION_ADVANCED_DRIVER', 'native'), // native only
        'motion' => [
            'fps' => env('VISION_MOTION_FPS', 2.0),
            'scale' => env('VISION_MOTION_SCALE', 320),
        ],
    ],
];


