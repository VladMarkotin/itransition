<?php
namespace App\Services\HandleDataServices;


class HandleDataService
{
    public function handle($record)
    {
        $filters = $this->defineFilters();
        foreach ($filters as $f) {
            $recordLength = count($record);
            $record = array_filter($record, $f, ARRAY_FILTER_USE_BOTH);
            if (count($record) != $recordLength) {
                return ;
            }
        }
        
        return $record;
    }

    private function defineFilters()
    {
        $filters = [
            //Any stock item which costs less that $5 and has less than 10 stock will not be imported
            'Cost in GBP' => (function ($el, $ind) {
                if ($ind == 'Cost in GBP') {
                    if (floatval($el) > 5) {
                        return $el;
                    }
                    //dd($el);
                    return ;
                }
                
                return $el;
            }),
            //and has less than 10 stock will not be imported
            'Stock' => (function ($el, $ind) {
                if ($ind == 'Stock') {
                    if (floatval($el) > 10) {
                        return $el;
                    }
                    return ;
                }
                
                return $el;
            }),
        ];

        return $filters;
    }
}