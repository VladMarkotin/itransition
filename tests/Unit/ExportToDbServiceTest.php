<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\ExportServices\ExportToDbService;

class ExportToDbServiceTest extends TestCase
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

    public function testInsertDataIntoDb()
    {
        $exportService = new ExportToDbService();
        putenv('DB_CHUNK_SIZE=2');

        // Создание тестовых данных
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

        DB::shouldReceive('table->insert')->times(2);
        $exportService->insertIntoDb($correctData);
        DB::assertNumberOfQueries(2);
    }

}
