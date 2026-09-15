<?php

namespace App\Filament\Admin\Resources\GivingProgrammes\Pages;

use App\Filament\Admin\Resources\GivingProgrammes\GivingProgrammeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGivingProgramme extends ViewRecord
{
    protected static string $resource = GivingProgrammeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
