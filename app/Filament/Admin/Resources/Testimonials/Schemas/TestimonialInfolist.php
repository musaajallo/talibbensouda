<?php

namespace App\Filament\Admin\Resources\Testimonials\Schemas;

use App\Models\Testimonial;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    TextEntry::make('quote')->columnSpanFull()->prose(),
                    TextEntry::make('name'),
                    TextEntry::make('role')->placeholder('—'),
                    IconEntry::make('featured')->boolean(),
                    IconEntry::make('published')->boolean(),
                    TextEntry::make('sort_order')->label('Sort order'),
                ]),

            Section::make('Submission')
                ->description('Sent as a link for someone to submit their own testimonial.')
                ->visible(fn (Testimonial $record): bool => $record->invite_token !== null)
                ->columns(2)
                ->components([
                    TextEntry::make('statusLabel')->label('Status')->state(fn (Testimonial $record) => $record->statusLabel())->badge(),
                    IconEntry::make('approved')->boolean(),
                    TextEntry::make('invite_email')->label('Invited email')->placeholder('—'),
                    TextEntry::make('invite_sent_at')->label('Invite sent')->dateTime()->placeholder('—'),
                    TextEntry::make('submitted_at')->label('Submitted')->dateTime()->placeholder('Not yet submitted'),
                ]),
        ]);
    }
}
