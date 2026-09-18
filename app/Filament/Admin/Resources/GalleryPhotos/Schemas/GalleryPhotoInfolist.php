<?php

namespace App\Filament\Admin\Resources\GalleryPhotos\Schemas;

use App\Models\GalleryPhoto;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class GalleryPhotoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Video')
                ->visible(fn (?GalleryPhoto $record): bool => $record?->isVideo() ?? false)
                ->components([
                    View::make('filament.admin.resources.gallery-photos.youtube-video'),
                ]),

            Section::make('Photo')
                ->hidden(fn (?GalleryPhoto $record): bool => $record?->isVideo() ?? false)
                ->components([
                    SpatieMediaLibraryImageEntry::make('photo')
                        ->collection('photo')
                        ->hiddenLabel()
                        ->imageHeight(320),
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
