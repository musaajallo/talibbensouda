<?php

namespace App\Filament\Admin\Resources\HeroSlides\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HeroSlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Image')
                ->components([
                    SpatieMediaLibraryFileUpload::make('image')
                        ->collection('image')
                        ->image()
                        ->imageEditor()
                        ->responsiveImages()
                        ->required()
                        ->hiddenLabel()
                        ->helperText('Landscape, at least 1600px wide. A 768w variant is generated automatically for phones.'),
                ]),

            Section::make('Details')
                ->columns(2)
                ->components([
                    TextInput::make('fallback_colour')
                        ->default('#0d1b38')
                        ->maxLength(9)
                        ->required()
                        ->helperText('Shown behind the image while it loads.'),
                    TextInput::make('position')
                        ->default('center top')
                        ->maxLength(30)
                        ->required()
                        ->helperText('CSS background-position, e.g. "center top" or "center".'),
                ]),
        ]);
    }
}
