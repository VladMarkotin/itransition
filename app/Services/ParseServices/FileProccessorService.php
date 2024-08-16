<?php

declare(strict_types=1);

namespace App\Services\ParseServices;

use App\Services\ParseServices\Contracts\FileParseContract;

class FileProccessorService
{
    private FileParseContract $parser;

    public function __construct(FileParseContract $parser)
    {
        $this->parser = $parser;
    }

    public function process(string $filePath = null)
    {
        if (file_exists($filePath)) {
            return $this->parser->parse($filePath);
        }

        dd('File doesn`t exist :(');

    }
}
