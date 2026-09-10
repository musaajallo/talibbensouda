<?php

namespace App\Filament\Admin\Resources\Testimonials\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    Textarea::make('quote')->required()->rows(4)->columnSpanFull(),
                    TextInput::make('name')->required()->maxLength(160),
                    TextInput::make('role')->maxLength(160)->helperText('e.g. "United States · 2022".'),
                    Toggle::make('featured')->helperText('Renders in the large card slot.'),
                    Toggle::make('published')->default(true),
                    TextInput::make('sort_order')->numeric()->default(0)->required(),
                ]),
        ]);
    }
}
