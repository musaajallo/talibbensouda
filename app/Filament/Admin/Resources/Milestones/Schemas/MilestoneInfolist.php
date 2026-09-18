<?php

namespace App\Filament\Admin\Resources\Milestones\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MilestoneInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    TextEntry::make('title')->columnSpanFull(),
                    TextEntry::make('occurred_on')->date(),
                    TextEntry::make('location')->placeholder('—'),
                    TextEntry::make('description')->columnSpanFull()->prose(),
                    IconEntry::make('published')->boolean(),
                ]),
        ]);
    }
}
