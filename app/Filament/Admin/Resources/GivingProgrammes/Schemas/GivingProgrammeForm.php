<?php

namespace App\Filament\Admin\Resources\GivingProgrammes\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GivingProgrammeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    TextInput::make('title')->required()->maxLength(160)->columnSpanFull(),
                    Textarea::make('description')->required()->rows(4)->columnSpanFull(),
                    TextInput::make('metric')->maxLength(120)
                        ->helperText('Figure shown at the foot of the card, e.g. "D3.8M to WDCs per year".'),
                    Toggle::make('published')->default(true),
                    TextInput::make('sort_order')->numeric()->default(0)->required(),
                ]),
        ]);
    }
}
