<?php
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
        $data = [];
        //Путь к файлу задается в .env файле 
        $systemPath = __DIR__.env('STORAGE_SRC');
        $filePath = $systemPath.env('FILE_SRC');
        if (file_exists($filePath)) {
            $data = $this->parser->parse($filePath);
            
            return $data;
        }
        dd('File doesn`t exist :(');

    }
}