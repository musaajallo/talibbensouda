<?php

namespace App\Filament\Admin\Resources\CommunityPhotos\Pages;

use App\Filament\Admin\Resources\CommunityPhotos\CommunityPhotoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCommunityPhoto extends ViewRecord
{
    protected static string $resource = CommunityPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
