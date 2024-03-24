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
        //Определяем, какой пасер будет работать с нашим файлом (иначе говоря, реализуем FileParseContract)
        $this->app->bind(FileParseContract::class, function ($app) {
            $filePath = __DIR__.env('STORAGE_SRC').env('FILE_SRC');
            $ext = pathinfo($filePath, PATHINFO_EXTENSION);
            //Логика определения необходимого паарсера на основе расширения файла
            switch ($ext) {
                case 'csv':  return new CsvParser( new HandleDataService);
                case 'xml': //здесь может быть, например, XMLParser
                    dd('I`ll be rady to parse xml file as soon as you provide me with XMLParser :)');
                    break;
                //..
                default: dd('We currently can`t work with such files :(');
            }
        });
    }
}