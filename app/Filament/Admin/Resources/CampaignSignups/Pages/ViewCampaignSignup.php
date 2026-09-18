<?php

namespace App\Filament\Admin\Resources\CampaignSignups\Pages;

use App\Filament\Admin\Resources\CampaignSignups\CampaignSignupResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCampaignSignup extends ViewRecord
{
    protected static string $resource = CampaignSignupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
