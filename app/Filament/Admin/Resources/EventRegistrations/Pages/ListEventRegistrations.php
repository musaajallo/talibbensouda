<?php

namespace App\Filament\Admin\Resources\EventRegistrations\Pages;

use App\Filament\Admin\Resources\EventRegistrations\EventRegistrationResource;
use Filament\Resources\Pages\ListRecords;

class ListEventRegistrations extends ListRecords
{
    protected static string $resource = EventRegistrationResource::class;
}
