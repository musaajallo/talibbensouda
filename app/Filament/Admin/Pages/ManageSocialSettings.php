<?php

namespace App\Filament\Admin\Pages;

use App\Settings\SocialSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSocialSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShare;

    protected static string $settings = SocialSettings::class;

    protected static ?string $navigationLabel = 'Social links';

    protected static ?string $title = 'Social links';

    protected static string|UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 2;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Profile URLs')
                ->description('A blank field hides that icon in the footer and on the contact page.')
                ->columns(2)
                ->components([
                    TextInput::make('facebook')->url()->prefixIcon('heroicon-o-link')->maxLength(255),
                    TextInput::make('x')->label('X (Twitter)')->url()->prefixIcon('heroicon-o-link')->maxLength(255),
                    TextInput::make('instagram')->url()->prefixIcon('heroicon-o-link')->maxLength(255),
                    TextInput::make('youtube')->url()->prefixIcon('heroicon-o-link')->maxLength(255),
                    TextInput::make('whatsapp')->label('WhatsApp')->url()->prefixIcon('heroicon-o-link')->maxLength(255),
                ]),
        ]);
    }
}
