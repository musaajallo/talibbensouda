<?php

namespace App\Filament\Admin\Resources\GalleryPhotos\Schemas;

use App\Models\GalleryPhoto;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class GalleryPhotoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // A YouTube-sourced entry has no file of its own — the video lives on
            // YouTube — so it gets a player instead of the uploader (which would
            // also demand a photo before it could be saved). Photo entries, and
            // anything created by hand here, keep the uploader.
            Section::make('Video')
                ->visible(fn (?GalleryPhoto $record): bool => $record?->isVideo() ?? false)
                ->components([
                    View::make('filament.admin.resources.gallery-photos.youtube-video'),
                ]),

            Section::make('Photo')
                ->hidden(fn (?GalleryPhoto $record): bool => $record?->isVideo() ?? false)
                ->components([
                    SpatieMediaLibraryFileUpload::make('photo')
                        ->collection('photo')
                        ->image()
                        ->imageEditor()
                        ->responsiveImages()
                        ->required()
                        ->hiddenLabel()
                        ->helperText('Landscape crop, at least 1280 × 720px.'),
                ]),

            Section::make('Details')
                ->columns(2)
                ->components([
                    TextInput::make('caption')->maxLength(255),
                    Select::make('category')
                        ->options(array_combine(GalleryPhoto::CATEGORIES, GalleryPhoto::CATEGORIES))
                        ->default('Projects')
                        ->required(),
                    Toggle::make('published')
                        ->default(true),
                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->required(),
                ]),
        ]);
    }
}
