<?php

namespace App\Filament\Admin\Resources\GivingProgrammes\Pages;

use App\Filament\Admin\Resources\GivingProgrammes\GivingProgrammeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGivingProgrammes extends ListRecords
{
    protected static string $resource = GivingProgrammeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
