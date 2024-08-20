<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\ParseServices\CsvParser;
use App\Services\HandleDataServices\HandleDataService;

class CsvParserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function testCsvParsing()
    {
        $csvParser = new CsvParser(new HandleDataService);
        $correctData = [
            [
                "strProductCode" => "P0002",
                "strProductName" => "Cd Player",
                "strProductDesc" => "Nice CD player",
                "price" => "50.12",
                "stock_level" => "11",
                "dtmDiscontinued" => null,
            ]
        ];
        $testPath = __DIR__.env('STORAGE_SRC').env('CORRECT_TEST_FILE_SRC');
        $data = $csvParser->parse($testPath);
        $this->assertEquals($correctData, $data, "diff:");

        // Тесты на успешное чтение данных и другие проверки
    }
}
