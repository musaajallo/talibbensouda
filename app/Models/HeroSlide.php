<?php

namespace App\Models;

use App\Support\Media\ResolvesPublicMediaUrl;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * A background slide on the home page hero. Order is the panel's drag-and-drop
 * `sort_order`; leave the table empty to fall back to the bundled hero photos
 * (see {@see heroSlides()}).
 *
 * @property string $fallback_colour
 * @property string $position
 * @property int $sort_order
 */
class HeroSlide extends Model implements HasMedia
{
    use InteractsWithMedia;
    use LogsActivity;
    use ResolvesPublicMediaUrl;

    protected $fillable = ['fallback_colour', 'position', 'sort_order'];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile()->useDisk('public');
    }

    /** 768w variant served to phones — the front end never fetches both sizes. */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('sm')
            ->performOnCollections('image')
            ->width(768);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['fallback_colour', 'position', 'sort_order'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    /** HTTPS-safe public URL for the full-size slide image, or null if missing. */
    public function imageUrl(): ?string
    {
        return $this->publicMediaUrl('image');
    }

    /** 768w variant, falling back to the full image when the conversion isn't ready. */
    public function imageSmUrl(): ?string
    {
        return $this->publicMediaUrl('image', 'sm') ?? $this->imageUrl();
    }

    /**
     * Configured slides with resolved image URLs, or a default set built from
     * the bundled hero images when the table is empty.
     *
     * @return array<int, array{img: string, img_sm: string, bg: string, pos: string}>
     */
    public static function heroSlides(): array
    {
        $configured = static::query()
            ->with('media')
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (self $slide) => filled($slide->imageUrl()))
            ->map(fn (self $slide) => [
                'img' => $slide->imageUrl(),
                'img_sm' => $slide->imageSmUrl(),
                'bg' => $slide->fallback_colour,
                'pos' => $slide->position,
            ])
            ->values()
            ->all();

        if ($configured !== []) {
            return $configured;
        }

        // Slides are anchored to the top so faces/heads aren't cropped as the
        // hero height varies across viewports.
        $defaults = [
            ['file' => 'hero-rally', 'bg' => '#0d1b38'],
            ['file' => 'hero-talib-desk', 'bg' => '#0d1b38'],
            ['file' => 'hero-masquerade', 'bg' => '#0a1525'],
            ['file' => 'hero-supporters', 'bg' => '#112044'],
            ['file' => 'hero-hall', 'bg' => '#091422'],
            ['file' => 'hero-victory', 'bg' => '#0d1b38'],
        ];

        return array_map(fn ($slide) => [
            'img' => asset("images/{$slide['file']}.webp"),
            'img_sm' => asset("images/{$slide['file']}-sm.webp"),
            'bg' => $slide['bg'],
            'pos' => 'center top',
        ], $defaults);
    }
}
