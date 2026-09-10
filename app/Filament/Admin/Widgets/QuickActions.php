<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Pages\ManageHomePage;
use App\Filament\Admin\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Admin\Resources\Events\EventResource;
use App\Filament\Admin\Resources\GalleryPhotos\GalleryPhotoResource;
use App\Models\ContactMessage;
use Filament\Widgets\Widget;

/**
 * Top-of-dashboard panel of action buttons. Sits above the stat cards so the
 * most-likely next step is one click away from the landing page. Inbox links
 * carry a "new in the last 7 days" badge.
 */
class QuickActions extends Widget
{
    protected string $view = 'filament.admin.widgets.quick-actions';

    protected static ?int $sort = -10;

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<int, array{label: string, icon: string, url: string, kind: string, badge?: int|null}>
     */
    public function getActions(): array
    {
        $weekAgo = now()->subWeek();

        return [
            [
                'label' => 'New event',
                'icon' => 'heroicon-o-calendar-days',
                'url' => EventResource::getUrl('create'),
                'kind' => 'create',
            ],
            [
                'label' => 'Add photo',
                'icon' => 'heroicon-o-photo',
                'url' => GalleryPhotoResource::getUrl('create'),
                'kind' => 'create',
            ],
            [
                'label' => 'Edit home page',
                'icon' => 'heroicon-o-home',
                'url' => ManageHomePage::getUrl(),
                'kind' => 'view',
            ],
            [
                'label' => 'Messages',
                'icon' => 'heroicon-o-inbox',
                'url' => ContactMessageResource::getUrl(),
                'kind' => 'view',
                'badge' => $this->newCount(ContactMessage::query()->where('created_at', '>=', $weekAgo)->count()),
            ],
            [
                'label' => 'Visit site',
                'icon' => 'heroicon-o-arrow-top-right-on-square',
                'url' => url('/'),
                'kind' => 'view',
            ],
        ];
    }

    protected function newCount(int $n): ?int
    {
        return $n > 0 ? $n : null;
    }
}
