<?php

namespace App\Filament\Admin\Widgets;

use App\Models\GalleryPhoto;
use App\Models\Project;
use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class ContentOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 3;
    }

    /**
     * @return array<int, Stat>
     */
    protected function getStats(): array
    {
        return [
            $this->publishedStat('Projects', Project::class, 'heroicon-m-briefcase'),
            $this->publishedStat('Gallery photos', GalleryPhoto::class, 'heroicon-m-photo'),
            $this->publishedStat('Testimonials', Testimonial::class, 'heroicon-m-chat-bubble-left-right'),
        ];
    }

    /**
     * @param  class-string<Model>  $model
     */
    protected function publishedStat(string $label, string $model, string $icon): Stat
    {
        $total = (int) $model::query()->count();
        $published = (int) $model::query()->where('published', true)->count();

        return Stat::make($label, (string) $published.' published')
            ->description($total === $published ? 'All live' : ($total - $published).' hidden')
            ->descriptionIcon($icon)
            ->color($published > 0 ? 'primary' : 'gray');
    }
}
