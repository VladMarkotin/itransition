<?php
namespace App\Services\ParseServices;


use \App\Services\ParseServices\Contracts\FileParseContract;
use League\Csv\Reader;
use League\Csv\Statement;
use \App\Services\HandleDataServices\HandleDataService;


class CsvParser implements FileParseContract 
{
    private $csvData = [];
    private $handleDataService = null;

    public function __construct(HandleDataService $handleDataService)
    {
        $this->handleDataService = $handleDataService;
    }

    public function parse(string $filePath): string
    {
        $fileContent = file_get_contents($filePath);
        $csv = Reader::createFromString($fileContent);
        $csv->setDelimiter(',');
        $csv->setHeaderOffset(0);
        $stmt = Statement::create();//->limit(25);
    
        //query your records from the document
        $records = $stmt->process($csv);
    //    dd($records);
        foreach ($records as $record) {
            if ( $this->handleDataService->handle($record)) {
                $this->csvData[] = $record;
            }
        }

        dd($this->csvData);
        return  $csv->toString();
    }
}