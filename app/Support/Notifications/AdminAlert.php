<?php

namespace App\Support\Notifications;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

/**
 * Sends a Filament database notification (the panel bell) to the admin team.
 * Used for the contact + event-registration inboxes so a submission doesn't sit
 * unseen — the forms don't email anyone.
 */
class AdminAlert
{
    public static function send(string $title, string $body, string $icon, string $url): void
    {
        $admins = User::role(['admin', 'super-admin'])->get();

        if ($admins->isEmpty()) {
            return;
        }

        Notification::make()
            ->title($title)
            ->body($body)
            ->icon($icon)
            ->actions([
                Action::make('view')->label('Open')->url($url)->markAsRead(),
            ])
            ->sendToDatabase($admins);
    }
}
