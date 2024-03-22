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
    protected $formatter = [];

    public function __construct()
    {
        $this->formatter['Discontinued'] = (function ($row) {
            if ($row['Discontinued'] == 'yes') {
                $row['Discontinued'] = (Carbon::now()->toDateTimeString());//->format('Y-m-d')
            } else {
                $row['Discontinued'] = null;
            }

            return $row;
        });
    }

    public function getConstraints() //$record
    {
        $constraints = Statement::create()
            ->select('Product Code', 'Product Name', 'Product Description','Cost in GBP', 'Stock', 'Discontinued')
            ->where(fn (array $record) => ( (float) $record['Cost in GBP'] > 5 && (float) $record['Cost in GBP'] < 1000))
            ->where(fn (array $record) => (int) $record['Stock'] >= 10);
            
        return $constraints;
    }

    public function getFormatter($index = null)
    {
        return ($index) ? $this->formatter[$index]: $this->formatter;
    }

    public function handle(array $record)
    {
        return $this->prepareHeadersForExportInDb($record);
    }
    
    private function prepareHeadersForExportInDb($record)
    {   
        return array_combine($this->fieldsInDb, $record);
    }
}