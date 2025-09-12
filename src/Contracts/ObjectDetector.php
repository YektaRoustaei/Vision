<?php

namespace LaravelVision\Contracts;

interface ObjectDetector
{
    /**
     * @return array<int, array{label:string,score:float,bbox:array{0:int,1:int,2:int,3:int}}>
     */
    public function detect(mixed $imageOrPath): array;
}


