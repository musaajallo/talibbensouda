<?php

namespace App\Filament\Admin\Resources\Projects\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Project')
                ->columns(2)
                ->components([
                    TextInput::make('tag')
                        ->maxLength(60)
                        ->helperText('Short kicker, e.g. "Waste" or "Roads".'),
                    TextInput::make('title')->required()->maxLength(160),
                    Textarea::make('summary')->rows(3)->maxLength(600)->columnSpanFull()
                        ->helperText('Short version shown on the home page card. Falls back to the full description.'),
                    Textarea::make('description')->required()->rows(6)->columnSpanFull()
                        ->helperText('Full text shown on the People\'s Mayor page.'),
                    Toggle::make('published')->default(true),
                    TextInput::make('sort_order')->numeric()->default(0)->required(),
                ]),

            Section::make('Image')
                ->columns(2)
                ->components([
                    SpatieMediaLibraryFileUpload::make('image')
                        ->collection('image')
                        ->image()
                        ->imageEditor()
                        ->responsiveImages()
                        ->hiddenLabel()
                        ->columnSpanFull(),
                    Toggle::make('image_fills_card')
                        ->label('Image fills the whole card')
                        ->helperText('Used on the People\'s Mayor page for hero-style project cards.'),
                ]),

            Section::make('Metrics')
                ->description('Shown on the People\'s Mayor project cards. Leave empty to hide.')
                ->components([
                    Repeater::make('metrics')
                        ->hiddenLabel()
                        ->schema([
                            TextInput::make('value')->required()->maxLength(40),
                            TextInput::make('label')->required()->maxLength(80),
                        ])
                        ->columns(2)
                        ->maxItems(4)
                        ->reorderable()
                        ->defaultItems(0)
                        ->addActionLabel('Add metric'),
                ]),
        ]);
    }
}
