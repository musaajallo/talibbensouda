<?php

namespace App\Filament\Admin\Resources\GivingProgrammes\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GivingProgrammeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    TextEntry::make('title')->columnSpanFull(),
                    TextEntry::make('description')->columnSpanFull()->prose(),
                    TextEntry::make('metric')->placeholder('—'),
                    IconEntry::make('published')->boolean(),
                    TextEntry::make('sort_order')->label('Sort order'),
                ]),
        ]);
    }
}
