<?php

namespace App\Filament\Admin\Resources\CampaignSignups\Pages;

use App\Filament\Admin\Resources\CampaignSignups\CampaignSignupResource;
use Filament\Resources\Pages\ListRecords;

class ListCampaignSignups extends ListRecords
{
    protected static string $resource = CampaignSignupResource::class;
}
