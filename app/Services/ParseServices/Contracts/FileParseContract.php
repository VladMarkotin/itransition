<?php

namespace App\Services\ParseServices\Contracts;

interface FileParseContract
{
    public function parse(string $filePath): array;
}
