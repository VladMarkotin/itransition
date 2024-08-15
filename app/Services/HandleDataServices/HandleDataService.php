<?php

declare(strict_types=1);

namespace App\Services\HandleDataServices;

use League\Csv\Reader;
use League\Csv\Statement;
use Carbon\Carbon;
use App\Services\ReportServices\ReportService;

class HandleDataService
{
    //Db Column`s list.
    protected $fieldsInDb = [
        'strProductCode',
        'strProductName',
        'strProductDesc',
        'price',
        'stock_level',
        'dtmDiscontinued'
    ];

    protected $formatters = [];

    public function __construct()
    {
        $this->prepareFormatters();
    }

    public function getConstraints()
    {
        /**
         * Create rules for checking lines in csv-file data. If not valid,
         * line would not be added to DB
         */
        return ( Statement::create()
            ->select('Product Code', 'Product Name', 'Product Description','Cost in GBP', 'Stock', 'Discontinued')
                ->where(function (array $record) {

                    if ((float) $record['Cost in GBP'] > 5) {
                        return true;
                    }

                    //add failed lines to report
                    ReportService::storeFailedRecord($record);

                    return false;
                })
                ->where(function (array $record) {

                    if ((float) $record['Cost in GBP'] < 1000) {
                        return true;
                    }

                    ReportService::storeFailedRecord($record);

                    return false;
                })
                ->where(function (array $record) {

                    if ((float) $record['Stock'] >= 10) {
                        return true;
                    }

                    ReportService::storeFailedRecord($record);

                    return false;
                })
        );
    }

    public function getFormatter($index = null)
    {
        return ($index) ? $this->formatters[$index]: $this->formatters;
    }

    public function handle(array $record) :array
    {
        return $this->prepareHeadersForExportInDb($record);
    }

    private function prepareHeadersForExportInDb($record) :array
    {
        return array_combine($this->fieldsInDb, $record);
    }

    private function prepareFormatters()
    {
        /**
         * format Discontinued-field
         */
        $this->formatters['Discontinued'] = (function ($row) {

            if ($row['Discontinued'] == 'yes') {
                $row['Discontinued'] = (Carbon::now()->toDateTimeString());
            } else {
                $row['Discontinued'] = null;
            }

            return $row;
        });
    }
}
