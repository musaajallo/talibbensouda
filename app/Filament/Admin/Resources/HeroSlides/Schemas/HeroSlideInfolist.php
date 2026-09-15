<?php

namespace App\Filament\Admin\Resources\HeroSlides\Schemas;

use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HeroSlideInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Image')
                ->components([
                    SpatieMediaLibraryImageEntry::make('image')
                        ->collection('image')
                        ->hiddenLabel()
                        ->imageHeight(320),
                ]),

            Section::make('Details')
                ->columns(2)
                ->components([
                    TextEntry::make('fallback_colour')->label('Fallback colour'),
                    TextEntry::make('position'),
                    TextEntry::make('sort_order')->label('Sort order'),
                ]),
        ]);
    }
}
