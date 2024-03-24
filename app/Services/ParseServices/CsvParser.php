<?php
namespace App\Services\ParseServices;


use \App\Services\ParseServices\Contracts\FileParseContract;
use League\Csv\Reader;
use \App\Services\HandleDataServices\HandleDataService;
use App\Services\ReportServices\ReportService;


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
        //createFromPath() здесь использовать уместнее, чем просто createFromString()
        //Это связано с оптимизацией загрузки и обработки csv-файла (если csv-файл большой такой вариант будет работать лучше)
        $csv = Reader::createFromPath($filePath)
          ->setDelimiter(',')
          ->setHeaderOffset(0)
          ->addFormatter($formatters['Discontinued']);
        ReportService::setRecordsAmount($csv->count() );
        //Проверки, указанные в ТЗ, будут формироваться здесь
        $constraints = $this->handleDataService->getConstraints();
        $filteredData = $constraints->process($csv);
        //а здесь мы уже получим готовый результат
        $records = $filteredData->getRecords();
        //получаю кол.во подходящих нам записей
        ReportService::addToReport('success', $filteredData->count());
        //получаю кол.во битых записей
        ReportService::addToReport('fail', ($csv->count() - $filteredData->count()) );
        foreach ($records as $record) {
            $this->csvData[] = $this->handleDataService->handle($record);
        }

        return $this->csvData;
    }
}