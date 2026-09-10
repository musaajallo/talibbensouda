<?php

namespace App\Filament\Admin\Pages;

use App\Settings\GeneralSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageGeneralSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string $settings = GeneralSettings::class;

    protected static ?string $navigationLabel = 'General';

    protected static ?string $title = 'General settings';

    protected static string|UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 1;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identity')
                ->icon('heroicon-o-identification')
                ->columns(2)
                ->components([
                    TextInput::make('site_name')->required()->maxLength(120),
                    TextInput::make('site_tagline')->label('Tagline')->maxLength(160),
                    FileUpload::make('site_logo')
                        ->image()
                        ->imageEditor()
                        ->disk('public')
                        ->directory('branding')
                        ->visibility('public')
                        ->maxSize(5120)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                        ->helperText('Shown in the site header. Leave empty to use the bundled logo.'),
                    FileUpload::make('favicon')
                        ->image()
                        ->disk('public')
                        ->directory('branding')
                        ->visibility('public')
                        ->maxSize(512)
                        ->acceptedFileTypes(['image/png', 'image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon']),
                ]),

            Section::make('Contact')
                ->icon('heroicon-o-envelope')
                ->columns(2)
                ->components([
                    TextInput::make('contact_email')->email()->required()->maxLength(190),
                    TextInput::make('contact_phone')->tel()->maxLength(40),
                    TextInput::make('based_in')->maxLength(120),
                    TextInput::make('response_time')->maxLength(120),
                ]),
        ]);
    }
}
