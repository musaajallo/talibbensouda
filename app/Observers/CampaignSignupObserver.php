<?php

namespace App\Observers;

use App\Filament\Admin\Resources\CampaignSignups\CampaignSignupResource;
use App\Models\CampaignSignup;
use App\Support\Notifications\AdminAlert;

class CampaignSignupObserver
{
    public function created(CampaignSignup $signup): void
    {
        AdminAlert::send(
            title: 'New sign-up from the pop-up',
            body: $signup->name.' — '.$signup->phone,
            icon: 'heroicon-o-user-plus',
            url: CampaignSignupResource::getUrl('view', ['record' => $signup]),
        );
    }
}
