<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property string $quote
 * @property string $name
 * @property string|null $role
 * @property bool $featured
 * @property bool $published
 * @property int $sort_order
 */
class Testimonial extends Model
{
    use LogsActivity;

    protected $fillable = ['quote', 'name', 'role', 'featured', 'published', 'sort_order'];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'featured', 'published', 'sort_order'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }
}
