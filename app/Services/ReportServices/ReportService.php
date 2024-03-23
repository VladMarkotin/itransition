<?php
namespace App\Services\ReportServices;


class ReportService
{
    protected static $summary = [
        'success' => 0,
        'fail' => 0,
        'proccessed' => 0,
    ];

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
}
