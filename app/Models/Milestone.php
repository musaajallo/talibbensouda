<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * A past record-of-delivery entry (an opening, a launch, an election) — the
 * "what's already happened" counterpart to `Event` ("what's scheduled next").
 * Deliberately has none of Event's scheduling/RSVP machinery: just a date,
 * a place and a fact.
 *
 * @property string $slug
 * @property string $title
 * @property Carbon $occurred_on
 * @property string|null $location
 * @property string $description
 * @property bool $published
 */
class Milestone extends Model
{
    use LogsActivity;

    protected $fillable = [
        'slug', 'title', 'occurred_on', 'location', 'description', 'published',
    ];

    protected function casts(): array
    {
        return [
            'occurred_on' => 'date',
            'published' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'occurred_on', 'location', 'description', 'published'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }
}
