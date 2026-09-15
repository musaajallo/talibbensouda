<?php

namespace App\Filament\Admin\Resources\Testimonials\Pages;

use App\Filament\Admin\Resources\Testimonials\TestimonialResource;
use App\Mail\TestimonialInviteMail;
use App\Models\Testimonial;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ListTestimonials extends ListRecords
{
    protected static string $resource = TestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendInvite')
                ->label('Send invite')
                ->icon('heroicon-o-paper-airplane')
                ->modalHeading('Send a testimonial invite')
                ->modalSubmitActionLabel('Send invite')
                ->schema([
                    TextInput::make('name')->required()->maxLength(160),
                    TextInput::make('email')->email()->required()->maxLength(190),
                ])
                ->action(function (array $data): void {
                    $testimonial = Testimonial::createInvite($data['name'], $data['email']);

                    try {
                        Mail::to($data['email'])->send(new TestimonialInviteMail($testimonial));
                    } catch (Throwable $e) {
                        // Don't leave an invite row behind for an email that
                        // never went anywhere.
                        $testimonial->delete();

                        report($e);

                        Notification::make()
                            ->title('Could not send the invite')
                            ->body($e->getMessage())
                            ->danger()
                            ->persistent()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Invite sent')
                        ->body("A submission link was emailed to {$data['email']}.")
                        ->success()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
