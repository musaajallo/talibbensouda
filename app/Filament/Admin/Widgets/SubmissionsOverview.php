<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\EventRegistration;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SubmissionsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

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
        $weekAgo = now()->subWeek();

        $newMessages = (int) ContactMessage::query()->where('created_at', '>=', $weekAgo)->count();
        $newRegistrations = (int) EventRegistration::query()->where('created_at', '>=', $weekAgo)->count();
        $upcomingEvents = (int) Event::query()->where('is_upcoming', true)->count();

        return [
            Stat::make('Messages this week', (string) $newMessages)
                ->description('All-time: '.ContactMessage::query()->count())
                ->descriptionIcon('heroicon-m-inbox')
                ->color($newMessages > 0 ? 'primary' : 'gray'),
            Stat::make('Event sign-ups this week', (string) $newRegistrations)
                ->description('All-time: '.EventRegistration::query()->count())
                ->descriptionIcon('heroicon-m-ticket')
                ->color($newRegistrations > 0 ? 'primary' : 'gray'),
            Stat::make('Upcoming events', (string) $upcomingEvents)
                ->description('Total events: '.Event::query()->count())
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($upcomingEvents > 0 ? 'success' : 'gray'),
        ];
    }
}
