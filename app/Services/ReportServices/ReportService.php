<?php

declare(strict_types=1);

namespace App\Services\ReportServices;


use Illuminate\Support\Facades\Log;

class ReportService
{
    protected static $summary = [
        'success' => 0,
        'fail' => 0,
        'proccessed' => 0,
    ];

    protected static $failedRecords = [];

    /**
     * Здесь формирую отчет об импорте в базу
     */
    public static function getReport() :string
    {
        $p = self::$summary['proccessed']." lines have been proccessed:\n";
        $s = "Successfuly handled: ".self::$summary['success'] ." lines\n";
        $f = "Failed: ". self::$summary['fail']." lines\n";
        $report = $p.$s.$f;

        return $report;
    }

    public static function setRecordsAmount($amount)
    {
        self::$summary['proccessed'] = $amount;
    }

    public static function addToReport($index, $amount)
    {
        self::$summary[$index] = $amount;
    }

    public static function storeFailedRecord($record)
    {
        if (!in_array($record, self::$failedRecords) ) {
            self::$failedRecords[] = $record;
        }
    }

    public static function getFailedRecords() :string
    {
        //Мне кажется что строки, которые не были добавлены в бд, лучше выводить в лог файл (т.к если csv-файл большой, то ошибок может быть много и выводить их в консоль неудобно)
        Log::info(json_encode( self::$failedRecords, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) );

        return 'Failed lines you can find in: '.storage_path().DIRECTORY_SEPARATOR .'logs'.DIRECTORY_SEPARATOR .'laravel.log';
    }
}
