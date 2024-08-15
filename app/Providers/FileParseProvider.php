<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ParseServices\Contracts\FileParseContract;
use App\Services\ParseServices\CsvParser;
use App\Services\HandleDataServices\HandleDataService;
use Symfony\Component\Console\Input\ArgvInput;

class FileParseProvider extends ServiceProvider
{
    public function register()
    {}

    public function boot(): FileParseContract
    {
        $this->app->bind(FileParseContract::class, function ($app) {
            $filePath = __DIR__.env('STORAGE_SRC').env('FILE_SRC');
            $input = new ArgvInput(null, null);

            if ($input->getParameterOption('--f')) {
                $fileParameter = pathinfo($input->getParameterOption('--f'));

                $ext = (isset($fileParameter['extension']) )
                    ? pathinfo($input->getParameterOption('--f'))['extension']
                    : pathinfo($filePath, PATHINFO_EXTENSION);
            } else {
                $ext = pathinfo($filePath, PATHINFO_EXTENSION);
            }

            switch ($ext) {
                case 'csv':  return new CsvParser( new HandleDataService);
                case 'xml': //for instance, here could be XMLParser
                    dd('I`ll be ready to parse xml file as soon as you provide me with XMLParser :)');
                    break;
                default: dd('We currently can`t work with such files :(');
            }
        });

        return new CsvParser( new HandleDataService);
    }
}
