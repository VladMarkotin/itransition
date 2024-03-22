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

    public function parse(string $filePath): array
    {
        $formatters = $this->handleDataService->getFormatter();
        $csv = Reader::createFromPath($filePath)
          ->setDelimiter(',')
          ->setHeaderOffset(0)
          ->addFormatter($formatters['Discontinued']);

        $constraints = $this->handleDataService->getConstraints();
        $filteredData = $constraints->process($csv);
        $records = $filteredData->getRecords();
        foreach ($records as $record) {
            $this->csvData[] = $this->handleDataService->handle($record);
        }
        //dd( $this->csvData);

        return $this->csvData;
    }
}