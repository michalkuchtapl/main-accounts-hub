<?php

namespace App\Console\Commands\ActivityLogs;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PruneActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = <<<'SIGNATUR'
        activity:prune
            {days=365}
    SIGNATUR;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prunes activity logs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = $this->argument('days') ?? 365;
        $this->info("Prunning logs older than $days days");

        ActivityLog::query()
            ->where('created_at', '<', Carbon::now()->subDays($days))
            ->delete();

        $this->info('Activity logs prunned');

        return Command::SUCCESS;
    }
}
