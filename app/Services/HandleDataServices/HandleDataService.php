<?php
namespace App\Services\HandleDataServices;


use League\Csv\Reader;
use League\Csv\Statement;
use Carbon\Carbon;
use App\Services\ReportServices\ReportService;

class HandleDataService
{
    //здесь названия столбцов в таблице в БД. Т.к названия столбцов в csv-файле и бд не совпадаю, то из этого массива мы получаем реальные названия для импорта в бд
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
         * Здесь формирую проверки для данных в csv. Если они не соответсвтвуют условиям, то в базу они не добавятся
         */
        $constraints = Statement::create()
            ->select('Product Code', 'Product Name', 'Product Description','Cost in GBP', 'Stock', 'Discontinued')
                ->where(function (array $record) {
                    if ((float) $record['Cost in GBP'] > 5) {
                        //все хорошо, эта запись нам подходит (по параметру Cost in GBP)
                        return true;
                    }
                    //Добавляю провалившие проверки данные в список
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
                });
            
        return $constraints;
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
         * Форматируем данные для столбца Discontinued согласно условия
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