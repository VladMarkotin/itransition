<?php

declare(strict_types=1);

namespace App\Services\ParseServices;

use App\Services\ParseServices\Contracts\FileParseContract;

class FileProccessorService
{
    private $parser;

    /**
     * Будет приходить объект парсера, который подходит для формата указанного файла (в нашем случае CSV).
     * Однако если файл будет иметь разрешение xml, сюда может быть передан объект парсера для xml (главное чтобы он был реализован)
     * За определение необходимого парсера отвечает файл app/Providers/FileParseProvider.php
     */
    public function __construct( FileParseContract $parser) //
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
