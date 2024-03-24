<?php

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
    protected $signature = "app:parse {-m=prod}";

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'This command will start csv file parsing proccess';

    protected $args = [];

    protected $fProcService = null;
    protected $dbService = null;

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
        $this->args['mode'] = $this->argument('-m');
        //Начинаем парсить данные из файла
        $this->info('Start importing process');
        $data = $this->fProcService->process();
        $this->info('Parsing from CSV file is completed');
        if ($this->args['mode'] == 'prod') {
            // Логика для сохранения данных в базу данных
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
