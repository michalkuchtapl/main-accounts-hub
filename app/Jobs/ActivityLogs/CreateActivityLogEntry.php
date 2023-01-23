<?php

namespace App\Jobs\ActivityLogs;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateActivityLogEntry implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected int $applicationId,
        protected string $title,
        protected Carbon $createdAt,
        protected ?int $modelId = null,
        protected ?string $modelClass = null,
        protected ?array $changes = []
    ) {
        $this->onQueue('activity-logs');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $log = new ActivityLog;
        $log->model_id = $this->modelId;
        $log->model_class = $this->modelClass;
        $log->application_id = $this->applicationId;
        $log->created_at = $this->createdAt;
        $log->name = $this->title;
        $log->changes = $this->changes;
        $log->save();
    }
}
