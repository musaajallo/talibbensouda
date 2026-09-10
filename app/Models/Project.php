<?php

namespace App\Models;

use App\Support\Media\ResolvesPublicMediaUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string|null $tag
 * @property string $title
 * @property string|null $summary
 * @property string $description
 * @property array<int, array{value: string, label: string}>|null $metrics
 * @property bool $image_fills_card
 * @property bool $published
 * @property int $sort_order
 */
class Project extends Model implements HasMedia
{
    use InteractsWithMedia;
    use LogsActivity;
    use ResolvesPublicMediaUrl;

    protected $fillable = ['key', 'tag', 'title', 'summary', 'description', 'metrics', 'image_fills_card', 'published', 'sort_order'];

    protected function casts(): array
    {
        return [
            'metrics' => 'array',
            'image_fills_card' => 'boolean',
            'published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile()->useDisk('public');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'tag', 'published', 'sort_order'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    public function imageUrl(): ?string
    {
        return $this->publicMediaUrl('image');
    }

    /** Card copy for the home page — the short summary when set, else the full description. */
    public function cardText(): string
    {
        return $this->summary ?: $this->description;
    }
}
