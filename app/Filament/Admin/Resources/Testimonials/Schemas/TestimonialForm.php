<?php

namespace App\Filament\Admin\Resources\Testimonials\Schemas;

use App\Models\Testimonial;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Submission status')
                ->visible(fn (?Testimonial $record): bool => $record?->invite_token !== null)
                ->components([
                    TextEntry::make('statusLabel')
                        ->hiddenLabel()
                        ->state(fn (?Testimonial $record) => $record?->statusLabel())
                        ->badge(),
                ]),

            Section::make()
                ->columns(2)
                ->components([
                    Textarea::make('quote')->required()->rows(4)->columnSpanFull(),
                    TextInput::make('name')->required()->maxLength(160),
                    TextInput::make('role')->maxLength(160)->helperText('e.g. "United States · 2022".'),
                    Toggle::make('featured')->helperText('Renders in the large card slot.'),
                    Toggle::make('approved')
                        ->default(true)
                        ->helperText('While off, the person this was sent to can still edit it via their link.'),
                    Toggle::make('published')
                        ->default(true)
                        ->helperText('Controls whether it shows on the site. Independent of approval.'),
                    TextInput::make('sort_order')->numeric()->default(0)->required(),
                ]),
        ]);
    }
}
