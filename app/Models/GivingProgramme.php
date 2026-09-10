<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * The "Community Programmes" cards on the Giving Back page.
 *
 * @property string $title
 * @property string $description
 * @property string|null $metric
 * @property bool $published
 * @property int $sort_order
 */
class GivingProgramme extends Model
{
    use LogsActivity;

    protected $fillable = ['key', 'title', 'description', 'metric', 'published', 'sort_order'];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'metric', 'published', 'sort_order'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }
}
