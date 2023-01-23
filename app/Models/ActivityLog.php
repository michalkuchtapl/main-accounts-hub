<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ActivityLog
 *
 * @property int $id
 * @property int|null $model_id
 * @property string|null $model_class
 * @property int $application_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property string $name
 * @property array|null $changes
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereChanges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereModelClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereName($value)
 * @mixin \Eloquent
 */
class ActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $dates = [
        'created_at',
    ];

    protected $casts = [
        'changes' => 'json',
    ];
}
