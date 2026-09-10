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
 * "On the ground" tiles reused on the home, People's Mayor and Giving Back pages.
 *
 * @property string|null $tag
 * @property string $caption
 * @property bool $published
 * @property int $sort_order
 */
class CommunityPhoto extends Model implements HasMedia
{
    use InteractsWithMedia;
    use LogsActivity;
    use ResolvesPublicMediaUrl;

    protected $fillable = ['tag', 'caption', 'published', 'sort_order'];

    protected function casts(): array
    {
        return [
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
            ->logOnly(['tag', 'caption', 'published', 'sort_order'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    public function imageUrl(): ?string
    {
        return $this->publicMediaUrl('photo');
    }
}
