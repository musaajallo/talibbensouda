<?php

namespace App\Filament\Admin\Resources\Milestones\Pages;

use App\Filament\Admin\Resources\Milestones\MilestoneResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMilestone extends ViewRecord
{
    protected static string $resource = MilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
