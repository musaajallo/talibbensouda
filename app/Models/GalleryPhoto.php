<?php

namespace App\Models;

use App\Settings\HomePageSettings;
use App\Support\Media\ResolvesPublicMediaUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
 * @property bool $featured_on_home
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
        'caption', 'category', 'type', 'youtube_video_id', 'wide', 'published', 'featured_on_home', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'wide' => 'boolean',
            'published' => 'boolean',
            'featured_on_home' => 'boolean',
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
            ->logOnly(['caption', 'category', 'type', 'youtube_video_id', 'wide', 'published', 'featured_on_home', 'sort_order'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    public function scopeVideos(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_VIDEO);
    }

    /**
     * The published videos marked "feature on home page", in the order the editor set on
     * the Home page content screen (HomePageSettings::$videos_order). Any not listed there —
     * newly featured ones — follow, in the Gallery's own order. One place decides the order
     * so the admin list and the home page can never disagree.
     *
     * @return Collection<int, static>
     */
    public static function featuredOnHome(): Collection
    {
        $position = array_flip(array_map('intval', app(HomePageSettings::class)->videos_order));

        return static::query()
            ->published()->videos()->where('featured_on_home', true)
            ->orderBy('sort_order')->orderBy('id')
            ->get()
            // sortBy is stable, so unlisted videos keep the Gallery order among themselves.
            ->sortBy(fn (self $video): int => $position[$video->id] ?? PHP_INT_MAX)
            ->values();
    }

    /**
     * The videos for the home page's video slider.
     *
     * If any PUBLISHED video is marked "feature on home page", only those show, in the
     * order set on the Home page content screen (see featuredOnHome()). If none is, every
     * published video does, in the Gallery's own order — so the section never goes empty
     * just because nothing has been featured yet. (A featured video that's been
     * unpublished doesn't count: it can't show, and it shouldn't silently switch the
     * section to "featured only" with nothing in it.)
     *
     * @return Collection<int, static>
     */
    public static function forHomeSlider(int $limit = 12): Collection
    {
        $featured = static::featuredOnHome();

        if ($featured->isNotEmpty()) {
            return $featured->take($limit);
        }

        return static::query()->published()->videos()->orderBy('sort_order')->orderBy('id')->take($limit)->get();
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

    /**
     * Privacy-enhanced embed URL for the gallery lightbox. Video rows only.
     * `autoplay=1` — opening the lightbox is itself the click/tap that starts
     * it; the iframe's own `allow="autoplay"` (gallery.blade.php) is what
     * actually lets the browser honour it. `playsinline=1` so it doesn't
     * force fullscreen on iOS the moment it starts.
     */
    public function youtubeEmbedUrl(): ?string
    {
        return $this->youtube_video_id
            ? "https://www.youtube-nocookie.com/embed/{$this->youtube_video_id}?autoplay=1&playsinline=1"
            : null;
    }
}
