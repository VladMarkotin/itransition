<?php
namespace App\Services\ExportServices;


use DB;

class ExportToDbService
{
    protected $table = 'tblproductdata';

    public function insertIntoDb(array $data)
    {
        $chunkSize = 500; // Размер пакета (сколько записей в одном запросе)
        $chunks = array_chunk($data, $chunkSize);
        //dd($chunks);

        foreach ($chunks as $chunk) {
            DB::table($this->table)->insert(
                $chunk
            );
        }
    }
}