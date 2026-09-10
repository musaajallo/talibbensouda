<?php

namespace App\Filament\Admin\Resources\GalleryPhotos\Schemas;

use App\Models\GalleryPhoto;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryPhotoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Photo')
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
                    Toggle::make('wide')
                        ->helperText('Spans two columns in the gallery grid.'),
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
