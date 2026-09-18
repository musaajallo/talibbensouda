<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Concerns\NormalisesSettingsData;
use App\Settings\SiteChromeSettings;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSiteChrome extends SettingsPage
{
    use NormalisesSettingsData;

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

            Section::make('Election countdown')
                ->description('A slim live countdown shown in the header on every page.')
                ->icon('heroicon-o-clock')
                ->columns(2)
                ->components([
                    Toggle::make('election_countdown_enabled')
                        ->label('Show the countdown')
                        ->columnSpanFull(),
                    TextInput::make('election_countdown_label')
                        ->required()
                        ->maxLength(80)
                        ->helperText('Shown next to the countdown, e.g. "To the 2026 Presidential Election".'),
                    DatePicker::make('election_date')
                        ->required()
                        ->native(false)
                        ->displayFormat('D j M Y')
                        ->closeOnDateSelection()
                        ->helperText('Counts down to midnight (Gambia time) on this date.'),
                ]),

            Section::make('Sign-up pop-up')
                ->description('Shown only once a visitor has spent some time browsing, and left alone for a while after they close it (never on top of the cookie banner). Collects name, phone and an optional location — no email, no pre-ticked boxes.')
                ->icon('heroicon-o-megaphone')
                ->columns(2)
                ->components([
                    Toggle::make('signup_popup_enabled')
                        ->label('Show the pop-up')
                        ->columnSpanFull(),
                    TextInput::make('signup_popup_heading')->required()->maxLength(80)->columnSpanFull(),
                    Textarea::make('signup_popup_body')->required()->rows(2)->maxLength(400)->columnSpanFull(),
                    TextInput::make('signup_popup_button_label')->required()->maxLength(40),
                    TextInput::make('signup_popup_delay_seconds')
                        ->label('Show it after')
                        ->numeric()
                        ->integer()
                        ->required()
                        ->minValue(0)
                        ->maxValue(3600)
                        ->suffix('seconds')
                        ->default(120)
                        ->helperText('Active browsing time, added up across pages — it only counts while the visitor is actually on the site (scrolling, tapping or moving the mouse), not a tab left open in the background. 120 = two minutes. 0 = as soon as the cookie banner has been answered.'),
                    TextInput::make('signup_popup_snooze_days')
                        ->label('If dismissed, ask again after')
                        ->numeric()
                        ->integer()
                        ->required()
                        ->minValue(0)
                        ->maxValue(365)
                        ->suffix('days')
                        ->default(14)
                        ->helperText('A visitor who closes the pop-up is left alone for this long, then gets the full browsing delay above before it can appear again. 0 = never ask again. Someone who signs up is never asked again.'),
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
