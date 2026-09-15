<?php

namespace App\Filament\Admin\Resources\GalleryPhotos\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryPhotoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Photo')
                ->components([
                    SpatieMediaLibraryImageEntry::make('photo')
                        ->collection('photo')
                        ->hiddenLabel(),
                ]),

            Section::make('Details')
                ->columns(2)
                ->components([
                    TextEntry::make('caption')->placeholder('—'),
                    TextEntry::make('category')->badge(),
                    IconEntry::make('published')->boolean(),
                    TextEntry::make('sort_order')->label('Sort order'),
                ]),
        ]);
    }
}
