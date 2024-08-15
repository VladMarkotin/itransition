<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\ParseServices\FileProccessorService;
use App\Services\ExportServices\ExportToDbService;
use App\Services\ReportServices\ReportService;

class Parse extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:parse {--m=prod} {--f=}';


    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'This command will start csv file parsing process. You can
     specify 2 arguments:
      "--m" for parsing mode
       "--f" for setting file name for parsing
       Both parameters are optional. By default  --m set as production and --f set
        as mentioned in .env file. Files for parsing must placed in /storage directory';

    protected $args = [];

    protected FileProccessorService $fProcService;
    protected ExportToDbService $dbService;

    public function __construct(FileProccessorService $fProcService,
                                ExportToDbService $dbService)
    {
        parent::__construct();
        $this->fProcService = $fProcService;
        $this->dbService = $dbService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->args['mode'] = $this->option('m');
        $this->args['path'] = $this->option('f');

        //start parsing
        $this->info('Start importing process');
        $systemPath = __DIR__.env('STORAGE_SRC');
        $path = ($this->args['path']) ?
            $systemPath.$this->args['path']
            : $systemPath.env('FILE_SRC');

        $data = $this->fProcService->process($path);
        $this->info('Parsing from CSV file is completed');

        if ($this->args['mode'] == 'prod') {
            // saving data to db
            $this->info('Start export data to database..');
            $this->dbService->insertIntoDb($data);
            $this->info('Export to database is completed');
            $this->info('Export`s report: ');
        } else {
            $this->info('Test mode is on so we will just show you parsing` info');
        }

        $this->info('-------------------------');
        $this->info( ReportService::getReport() );
        $this->info('-------------------------');
        $this->info(ReportService::getFailedRecords());

    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
