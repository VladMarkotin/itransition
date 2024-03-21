<?php
namespace App\Services\ParseServices;


//use App\Services\ParseServices\CsvParser;
use App\Services\ParseServices\Contracts\FileParseContract;

class FileProccessorService
{
    private $parser;

    public function __construct(FileParseContract $parser) //
    {
        $this->parser = $parser;
    }

    public function processFile(string $filePath = null)
    {
        $filePath = ($filePath) ?:  env('FILE_SRC');
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        //Логика определения необходимого апарсера
        // $parser = null;
        // switch ($ext) {
        //     case 'csv': $this->parser = new CsvParser();
        //         break;
        //     default: $this->parser = new CsvParser();
        // }
        //dd($filePath);
        $data = $this->parser->parse($filePath);
        
        //// Логика для сохранения данных в базу данных
        return $data;
    }
}