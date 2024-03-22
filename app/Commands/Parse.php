<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\ParseServices\FileProccessorService;
use App\Services\ExportServices\ExportToDbService;


class Parse extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:parse {file?} {mode?}';

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
        $this->args['file'] = $this->argument('file');
        $this->args['mode'] = $this->argument('mode');
        //Начинаем парсить данные из файла
        $this->info('Start importing process');
        $data = $this->fProcService->process($this->args['file']);
        $this->info('Parsing from CSV file is completed');
        // Логика для сохранения данных в базу данных
        $this->info('Start export data to database..');
        $this->dbService->insertIntoDb($data);
        $this->info('Export to database is completed');

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
