<?php

namespace App\Filament\Admin\Resources\GalleryPhotos\Pages;

use App\Filament\Admin\Actions\AddYoutubeVideosByLinkAction;
use App\Filament\Admin\Resources\GalleryPhotos\GalleryPhotoResource;
use App\Models\GalleryPhoto;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListGalleryPhotos extends ListRecords
{
    protected static string $resource = GalleryPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            AddYoutubeVideosByLinkAction::make(),
            CreateAction::make(),
        ];
    }

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All')
                ->badge(GalleryPhoto::count()),
        ];

        foreach (GalleryPhoto::CATEGORIES as $category) {
            $tabs[$category] = Tab::make($category)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category', $category))
                ->badge(GalleryPhoto::where('category', $category)->count());
        }

        return $tabs;
    }
}
