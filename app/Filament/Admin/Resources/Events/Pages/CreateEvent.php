<?php

namespace App\Filament\Admin\Resources\Events\Pages;

use App\Filament\Admin\Resources\Events\Concerns\HandlesEventSchedule;
use App\Filament\Admin\Resources\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    use HandlesEventSchedule;

    protected static string $resource = EventResource::class;
}
