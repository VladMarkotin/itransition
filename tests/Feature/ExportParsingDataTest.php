<?php

namespace Tests\Feature;

//use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class ExportParsingDataTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExportParsingData()
    {
        $product = [
            "strProductCode" => "P00022",
            "strProductName" => "Cd Player",
            "strProductDesc" => "Nice CD player",
            "price" => "50.12",
            "stock_level" => "11",
            "dtmDiscontinued" => null,
        ];

        // Вставляем данные в таблицу 'products'
        DB::table('tblproductdata')->insert($product);

        // Проверяем, что данные были успешно вставлены в базу данных
        $this->assertDatabaseHas('tblproductdata', $product);
    }
}
