<?php

declare(strict_types=1);

namespace App\Services\ExportServices;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;

class ExportToDbService
{
    protected $table = 'tblproductdata';

    public function insertIntoDb(array $data): bool
    {
        $chunkSize = env('DB_CHUNK_SIZE');
        $chunks = array_chunk($data, (int)$chunkSize);

        foreach ($chunks as $chunk) {
            try {
                DB::table($this->table)->insert(
                    $chunk
                );
            } catch (QueryException $e) {
                dd("Error during exporting to database. Message:", $e->errorInfo);
            }
        }

        return true;
    }
}
