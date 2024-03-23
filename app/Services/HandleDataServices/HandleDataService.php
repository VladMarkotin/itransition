<?php
namespace App\Services\HandleDataServices;


use League\Csv\Reader;
use League\Csv\Statement;
use Carbon\Carbon;

class HandleDataService
{
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
        $constraints = Statement::create()
            ->select('Product Code', 'Product Name', 'Product Description','Cost in GBP', 'Stock', 'Discontinued')
                ->where(function (array $record) {
                    if ((float) $record['Cost in GBP'] > 5) {
                        return true;
                    }
                }) 
                ->where(function (array $record) {
                    if ((float) $record['Cost in GBP'] < 1000) {
                        return true;
                    }
                })
                ->where(function (array $record) {
                    if ((float) $record['Stock'] >= 10) {
                        return true;
                    }
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