<?php
namespace App\Services\ParseServices;


use App\Services\ParseServices\Contracts\FileParseContract;

class FileProccessorService
{
    private $parser;

    public function __construct( FileParseContract $parser) //
    {
        $this->parser = $parser;
    }

    public function process(string $filePath = null)
    {
        $systemPath = __DIR__.env('STORAGE_SRC');
        $filePath = ($filePath) ? $systemPath.$filePath:  env('FILE_SRC');
        $data = $this->parser->parse($filePath);

        return $data;
    }
}