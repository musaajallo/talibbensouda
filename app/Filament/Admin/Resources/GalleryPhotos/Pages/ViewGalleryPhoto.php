<?php

namespace App\Filament\Admin\Resources\GalleryPhotos\Pages;

use App\Filament\Admin\Resources\GalleryPhotos\GalleryPhotoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGalleryPhoto extends ViewRecord
{
    protected static string $resource = GalleryPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
