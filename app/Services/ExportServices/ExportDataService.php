<?php

declare(strict_types=1);

namespace App\Services\ExportServices;

use Illuminate\Support\Facades\DB;

class ExportDataService
{
    protected $table = 'tblproductdata';

    public function clearDataFromDb(): int
    {
        $lines = DB::table($this->table)->count();
        DB::table($this->table)->truncate();

        return $lines;
    }
}
