<?php

namespace App\Concerns;

use App\Jobs\ActivityLogs\CreateActivityLogEntry;
use App\Models\Application;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    public function log(Application $application, string $title, ?Model $model = null, ?array $changes = []): void
    {
        dispatch(new CreateActivityLogEntry(
            applicationId: $application->id,
            title: $title,
            createdAt: Carbon::now(),
            modelId: $model?->getKey(),
            modelClass: get_class($model),
            changes: $changes
        ));
    }
}
