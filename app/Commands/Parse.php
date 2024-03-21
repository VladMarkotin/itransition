<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\ParseServices\FileProccessorService;


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

    public function __construct(FileProccessorService $fProcService)
    {
        parent::__construct();
        $this->fProcService = $fProcService;
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
        $this->info('This command will start parsing process');
        $str = $this->fProcService->processFile($this->args['file']);
        $this->info($str);
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
