<?php

namespace Tests\Feature;

use Symfony\Component\Console\Output\BufferedOutput;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class ExportDataCommandTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $test = Artisan::call('app:parse');
        $this->assertEquals(0, $test);
    }

    public function testParseWithParams()
    {
        $output = new BufferedOutput();
        $exitCode = Artisan::call('app:parse', [
            '--f' => 'stock3.csv',
            '--m' => 'test',
        ], $output);
        $this->assertEquals(0, $exitCode);
        echo "Output test`s text: \n".$output->fetch();
    }
}
