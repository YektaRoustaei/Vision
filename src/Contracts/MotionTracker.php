<?php

namespace LaravelVision\Contracts;

interface MotionTracker
{
    /**
     * Returns a lightweight summary of motion regions per frame.
     * @return iterable<int, array<int, array{bbox:array{0:int,1:int,2:int,3:int}, score:float}>>
     */
    public function track(string $videoPath): iterable;
}


