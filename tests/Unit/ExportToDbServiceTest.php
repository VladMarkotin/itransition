<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;
use App\Services\ExportServices\ExportToDbService;
use Mockery;
use Illuminate\Support\Facades\DB;

class ExportToDbServiceTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testInsertDataIntoDb()
    {
        Facade::setFacadeApplication(app());
        putenv('DB_CHUNK_SIZE=2');

        $correctData = [
            [
                "strProductCode" => "P00022",
                "strProductName" => "Cd Player",
                "strProductDesc" => "Nice CD player",
                "price" => "50.12",
                "stock_level" => "11",
                "dtmDiscontinued" => null,
            ],
        ];

        $db = Mockery::mock('alias:'.DB::class);
        $db->shouldReceive('table')->andReturnSelf();
        $db->shouldReceive('insert')
            ->once()
            ->withArgs([$correctData])
            ->andReturn(true);

        $service = new ExportToDbService();
        $result = $service->insertIntoDb($correctData);
        $this->assertTrue($result);

    }

}
