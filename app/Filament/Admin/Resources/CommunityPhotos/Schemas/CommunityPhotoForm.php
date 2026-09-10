<?php

namespace App\Filament\Admin\Resources\CommunityPhotos\Schemas;

use App\Models\CommunityPhoto;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommunityPhotoForm
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
                        ->hiddenLabel()
                        ->helperText('Landscape crop, at least 1000 × 750px.'),
                ]),

            Section::make('Details')
                ->columns(2)
                ->components([
                    Select::make('group')
                        ->options(CommunityPhoto::GROUPS)
                        ->default(CommunityPhoto::GROUP_MUNICIPALITY)
                        ->required()
                        ->helperText('Which pages this tile appears on.'),
                    TextInput::make('tag')->maxLength(40)->helperText('Overlay label, e.g. "Library".'),
                    TextInput::make('caption')->required()->maxLength(200)->columnSpanFull(),
                    Toggle::make('published')->default(true),
                    TextInput::make('sort_order')->numeric()->default(0)->required(),
                ]),
        ]);
    }
}
