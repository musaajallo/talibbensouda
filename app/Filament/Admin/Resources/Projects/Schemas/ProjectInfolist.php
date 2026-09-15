<?php

namespace App\Filament\Admin\Resources\Projects\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Project')
                ->columns(2)
                ->components([
                    TextEntry::make('tag')->placeholder('—'),
                    TextEntry::make('title'),
                    TextEntry::make('summary')->placeholder('—')->columnSpanFull()->prose(),
                    TextEntry::make('description')->columnSpanFull()->prose(),
                    IconEntry::make('published')->boolean(),
                    TextEntry::make('sort_order')->label('Sort order'),
                ]),

            Section::make('Image')
                ->columns(2)
                ->components([
                    SpatieMediaLibraryImageEntry::make('image')
                        ->collection('image')
                        ->hiddenLabel()
                        ->imageHeight(320)
                        ->columnSpanFull(),
                    IconEntry::make('image_fills_card')
                        ->label('Image fills the whole card')
                        ->boolean(),
                ]),

            Section::make('Metrics')
                ->components([
                    RepeatableEntry::make('metrics')
                        ->hiddenLabel()
                        ->schema([
                            TextEntry::make('value'),
                            TextEntry::make('label'),
                        ])
                        ->columns(2)
                        ->placeholder('—'),
                ]),
        ]);
    }
}
