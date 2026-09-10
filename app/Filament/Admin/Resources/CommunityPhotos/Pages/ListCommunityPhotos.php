<?php

namespace App\Filament\Admin\Resources\CommunityPhotos\Pages;

use App\Filament\Admin\Resources\CommunityPhotos\CommunityPhotoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCommunityPhotos extends ListRecords
{
    protected static string $resource = CommunityPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
