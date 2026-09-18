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
 * Despite the name, this also holds YouTube videos picked in the admin's
 * "Import YouTube Videos" page (App\Filament\Admin\Pages\ImportYoutubeVideos)
 * — `type` distinguishes the two. Kept as one model/table rather than a
 * parallel GalleryVideo one so the public gallery's category filter, sort
 * order, publish toggle, etc. all keep working identically for both; a video
 * row just has `youtube_video_id` set and no `photo` media.
 *
 * @property string|null $caption
 * @property string $category
 * @property string $type
 * @property string|null $youtube_video_id
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

    public const TYPE_PHOTO = 'photo';

    public const TYPE_VIDEO = 'video';

    protected $fillable = [
        'caption', 'category', 'type', 'youtube_video_id', 'wide', 'published', 'sort_order',
    ];

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
            ->logOnly(['caption', 'category', 'type', 'youtube_video_id', 'wide', 'published', 'sort_order'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    public function isVideo(): bool
    {
        return $this->type === self::TYPE_VIDEO;
    }

    /** HTTPS-safe public URL for the uploaded photo, or null if missing. */
    public function imageUrl(): ?string
    {
        return $this->publicMediaUrl('photo');
    }

    /** The photo, or a video's YouTube thumbnail — whichever this row is. */
    public function thumbnailUrl(): ?string
    {
        if ($this->isVideo()) {
            return "https://i.ytimg.com/vi/{$this->youtube_video_id}/hqdefault.jpg";
        }

        return $this->imageUrl();
    }

    /** Privacy-enhanced embed URL for the gallery lightbox. Video rows only. */
    public function youtubeEmbedUrl(): ?string
    {
        return $this->youtube_video_id
            ? "https://www.youtube-nocookie.com/embed/{$this->youtube_video_id}"
            : null;
    }
}
