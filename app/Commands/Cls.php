<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\ExportServices\ExportDataService;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class Cls extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:cls';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Clear export data from db';

    private ExportDataService $exportDataService;

    public function __construct(ExportDataService  $exportDataService)
    {
        parent::__construct();
        $this->exportDataService = $exportDataService;
    }

    protected function beforeExecution()
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Are you sure you want to clear all exported data from database? (yes/no)', false);

        if ($helper->ask($this->input, $this->output, $question)) {
            $this->output->writeln('Action canceled.');

            return true;
        }

        return false;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->beforeExecution()) {
            $this->info('Start clearing data from database...');
            $clearedLines = $this->exportDataService->clearDataFromDb();
            $this->info("Clearing has been finished. $clearedLines lines were successfully deleted");
        }
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
