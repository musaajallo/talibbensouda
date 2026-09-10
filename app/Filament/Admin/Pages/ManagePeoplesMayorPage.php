<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Concerns\NormalisesSettingsData;
use App\Settings\PeoplesMayorPageSettings;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManagePeoplesMayorPage extends SettingsPage
{
    use NormalisesSettingsData;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static string $settings = PeoplesMayorPageSettings::class;

    protected static ?string $navigationLabel = "People's Mayor page";

    protected static ?string $title = "People's Mayor page content";

    protected static string|UnitEnum|null $navigationGroup = 'Page content';

    protected static ?int $navigationSort = 20;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hero')
                ->columns(2)
                ->components([
                    TextInput::make('hero_eyebrow')->maxLength(120),
                    TextInput::make('hero_title')->maxLength(160),
                    Textarea::make('hero_subtitle')->rows(2)->maxLength(400)->columnSpanFull(),
                ]),

            Section::make('Impact stats')
                ->components([
                    Repeater::make('impact_stats')->hiddenLabel()
                        ->schema([
                            TextInput::make('value')->required()->maxLength(12),
                            TextInput::make('suffix')->maxLength(8),
                            TextInput::make('label')->required()->maxLength(120),
                        ])
                        ->columns(3)->reorderable()->defaultItems(0)->addActionLabel('Add stat'),
                ]),

            Section::make('Pull quote')
                ->components([
                    Textarea::make('pull_quote')->rows(3)->maxLength(500),
                    TextInput::make('pull_quote_attribution')->maxLength(200),
                ]),

            Section::make('Community photo grid')
                ->columns(2)
                ->components([
                    TextInput::make('community_eyebrow')->maxLength(120),
                    TextInput::make('community_headline')->maxLength(160),
                    Textarea::make('community_lead')->rows(2)->maxLength(400)->columnSpanFull(),
                ]),

            Section::make('Note on figures')
                ->components([
                    Textarea::make('figures_note')->rows(3)->maxLength(800),
                ]),

            Section::make('Closing call to action')
                ->columns(2)
                ->components([
                    TextInput::make('cta_headline')->maxLength(160)->columnSpanFull(),
                    Textarea::make('cta_lead')->rows(2)->maxLength(400)->columnSpanFull(),
                    TextInput::make('cta_primary_label')->maxLength(60),
                    TextInput::make('cta_primary_url')->maxLength(255),
                    TextInput::make('cta_secondary_label')->maxLength(60),
                    TextInput::make('cta_secondary_url')->maxLength(255),
                ]),
        ]);
    }
}
