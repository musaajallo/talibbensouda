<?php

namespace App\Observers;

use App\Filament\Admin\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use App\Support\Notifications\AdminAlert;
use Illuminate\Support\Str;

class ContactMessageObserver
{
    public function created(ContactMessage $message): void
    {
        $isJoin = $message->type === 'join';

        AdminAlert::send(
            title: $isJoin ? 'New "join the movement" sign-up' : 'New contact message',
            body: Str::limit(trim($message->name.' — '.($message->subject ?: $message->message)), 90),
            icon: $isJoin ? 'heroicon-o-user-plus' : 'heroicon-o-inbox-arrow-down',
            url: ContactMessageResource::getUrl('view', ['record' => $message]),
        );
    }
}
