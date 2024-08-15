<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ParseServices\Contracts\FileParseContract;
use App\Services\ParseServices\CsvParser;
use App\Services\HandleDataServices\HandleDataService;

class FileParseProvider extends ServiceProvider
{
    public function register(): FileParseContract
    {
        $this->app->bind(FileParseContract::class, function ($app) {
            $filePath = __DIR__.env('STORAGE_SRC').env('FILE_SRC');
            $ext = pathinfo($filePath, PATHINFO_EXTENSION);

            //define parser base on file extension
            switch ($ext) {
                case 'csv':  return new CsvParser( new HandleDataService);
                case 'xml': //for instance, here could be XMLParser
                    dd('I`ll be ready to parse xml file as soon as you provide me with XMLParser :)');
                    break;
                //..
                default: dd('We currently can`t work with such files :(');
            }

            return new CsvParser( new HandleDataService);
        });

        return new CsvParser( new HandleDataService);
    }
}
