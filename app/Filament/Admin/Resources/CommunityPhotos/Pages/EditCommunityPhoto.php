<?php

namespace App\Filament\Admin\Resources\CommunityPhotos\Pages;

use App\Filament\Admin\Resources\CommunityPhotos\CommunityPhotoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCommunityPhoto extends EditRecord
{
    protected static string $resource = CommunityPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
