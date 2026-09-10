<?php

namespace App\Filament\Admin\Resources\CommunityPhotos\Pages;

use App\Filament\Admin\Resources\CommunityPhotos\CommunityPhotoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCommunityPhoto extends CreateRecord
{
    protected static string $resource = CommunityPhotoResource::class;
}
