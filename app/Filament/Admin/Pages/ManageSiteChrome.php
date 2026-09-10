<?php

namespace App\Filament\Admin\Pages;

use App\Settings\SiteChromeSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSiteChrome extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static string $settings = SiteChromeSettings::class;

    protected static ?string $navigationLabel = 'Header & footer';

    protected static ?string $title = 'Header & footer';

    protected static string|UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 3;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('"Join Party" call to action')
                ->icon('heroicon-o-user-plus')
                ->columns(2)
                ->components([
                    TextInput::make('join_party_label')->required()->maxLength(60),
                    TextInput::make('join_party_url')->url()->required()->maxLength(255),
                ]),

            Section::make('Footer')
                ->icon('heroicon-o-bars-3-bottom-left')
                ->columns(2)
                ->components([
                    TextInput::make('footer_tagline')->required()->maxLength(120),
                    TextInput::make('copyright_name')->required()->maxLength(120)
                        ->helperText('Shown as "© <year> <name>. All rights reserved."'),
                ]),
        ]);
    }
}
