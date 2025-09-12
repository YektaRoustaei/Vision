<?php

namespace LaravelVision\Contracts;

interface FaceRecognizer
{
    /** @return array<int, array{bbox:array{0:int,1:int,2:int,3:int}, landmarks?:array}> */
    public function detectFaces(mixed $imageOrPath): array;

    /** @return string Binary/encoded embedding representation */
    public function encodeFace(mixed $imageOrPath, array $bbox): string;

    public function compareEmbeddings(string $embeddingA, string $embeddingB): float; // distance/similarity
}


