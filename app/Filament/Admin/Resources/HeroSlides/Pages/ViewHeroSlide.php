<?php

namespace App\Filament\Admin\Resources\HeroSlides\Pages;

use App\Filament\Admin\Resources\HeroSlides\HeroSlideResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHeroSlide extends ViewRecord
{
    protected static string $resource = HeroSlideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
