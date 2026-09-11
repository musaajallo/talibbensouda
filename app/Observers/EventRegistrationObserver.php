<?php

namespace App\Observers;

use App\Filament\Admin\Resources\EventRegistrations\EventRegistrationResource;
use App\Models\EventRegistration;
use App\Support\Notifications\AdminAlert;
use Illuminate\Support\Str;

class EventRegistrationObserver
{
    public function created(EventRegistration $registration): void
    {
        AdminAlert::send(
            title: 'New event registration',
            body: Str::limit(trim($registration->name.' — '.$registration->event), 90),
            icon: 'heroicon-o-ticket',
            url: EventRegistrationResource::getUrl('view', ['record' => $registration]),
        );
    }
}
