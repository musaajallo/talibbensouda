<?php

namespace App\Support\Notifications;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;

/**
 * Sends a Filament database notification (the panel bell) to the admin team.
 * Used for the contact, event-registration and campaign-signup inboxes so a
 * submission doesn't sit unseen — the forms don't email anyone.
 */
class AdminAlert
{
    public static function send(string $title, string $body, string $icon, string $url): void
    {
        // User::role() throws RoleDoesNotExist rather than returning empty if
        // the role itself was never seeded — a real possibility on a fresh
        // environment before RolesAndPermissionsSeeder has run. A public form
        // should never 500 just because that hasn't happened yet.
        if (! Role::where('name', 'admin')->exists() && ! Role::where('name', 'super-admin')->exists()) {
            return;
        }

        try {
            $admins = User::role(['admin', 'super-admin'])->get();
        } catch (RoleDoesNotExist) {
            return;
        }

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
