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
 * @property string|null $caption
 * @property string $category
 * @property bool $wide
 * @property bool $published
 * @property int $sort_order
 */
class GalleryPhoto extends Model implements HasMedia
{
    use InteractsWithMedia;
    use LogsActivity;
    use ResolvesPublicMediaUrl;

    /** Categories offered in the admin + used as the public filter tabs. */
    public const CATEGORIES = ['Projects', 'Community', 'Events', 'Partners'];

    protected $fillable = ['caption', 'category', 'wide', 'published', 'sort_order'];

    protected function casts(): array
    {
        return [
            'wide' => 'boolean',
            'published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile()->useDisk('public');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['caption', 'category', 'wide', 'published', 'sort_order'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    /** HTTPS-safe public URL for the uploaded photo, or null if missing. */
    public function imageUrl(): ?string
    {
        return $this->publicMediaUrl('photo');
    }
}
