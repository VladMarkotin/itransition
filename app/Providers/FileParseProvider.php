<?php
namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Services\ParseServices\Contracts\FileParseContract;
use App\Services\ParseServices\CsvParser;
use App\Services\HandleDataServices\HandleDataService;

class FileParseProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(FileParseContract::class, function ($app) {
            $filePath = __DIR__.env('STORAGE_SRC').env('FILE_SRC');
            $ext = pathinfo($filePath, PATHINFO_EXTENSION);
            //Логика определения необходимого паарсера
            switch ($ext) {
                case 'csv':  return new CsvParser( new HandleDataService);
                case 'xml': //здесь может быть, например, XMLParser
                    break;
                //..
                default: return new CsvParser(new HandleDataService);
            }
        });
    }
}