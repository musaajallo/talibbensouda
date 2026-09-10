<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Concerns\NormalisesSettingsData;
use App\Settings\GivingBackPageSettings;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageGivingBackPage extends SettingsPage
{
    use NormalisesSettingsData;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHandRaised;

    protected static string $settings = GivingBackPageSettings::class;

    protected static ?string $navigationLabel = 'Giving Back page';

    protected static ?string $title = 'Giving Back page content';

    protected static string|UnitEnum|null $navigationGroup = 'Page content';

    protected static ?int $navigationSort = 30;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hero')
                ->columns(2)
                ->components([
                    TextInput::make('hero_eyebrow')->maxLength(120),
                    TextInput::make('hero_title')->maxLength(160),
                    Textarea::make('hero_subtitle')->rows(2)->maxLength(500)->columnSpanFull(),
                ]),

            Section::make('Intro')
                ->columns(2)
                ->components([
                    TextInput::make('intro_eyebrow')->maxLength(120),
                    TextInput::make('intro_headline')->maxLength(160)
                        ->helperText('A newline breaks the heading across two lines.'),
                    Textarea::make('intro_body')->rows(6)->columnSpanFull()
                        ->helperText('Separate paragraphs with a blank line.'),
                    Textarea::make('intro_quote')->rows(2)->maxLength(300),
                    TextInput::make('intro_quote_attribution')->maxLength(200),
                    Repeater::make('intro_stats')->label('Mission stats')->columnSpanFull()
                        ->schema([
                            TextInput::make('value')->required()->maxLength(16),
                            TextInput::make('label')->required()->maxLength(160),
                        ])
                        ->columns(2)->reorderable()->defaultItems(0)->addActionLabel('Add stat'),
                ]),

            Section::make('Programmes section header')
                ->columns(2)
                ->components([
                    TextInput::make('programmes_eyebrow')->maxLength(120),
                    TextInput::make('programmes_headline')->maxLength(160),
                    Textarea::make('programmes_lead')->rows(2)->maxLength(400)->columnSpanFull()
                        ->helperText('The cards themselves live under Content → Giving programmes.'),
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

            Section::make('Community photos')
                ->columns(2)
                ->components([
                    TextInput::make('community_eyebrow')->maxLength(120),
                    TextInput::make('community_headline')->maxLength(160),
                    Textarea::make('community_lead')->rows(2)->maxLength(400)->columnSpanFull()
                        ->helperText('Tiles: Content → Community photos, group "Giving Back".'),
                ]),

            Section::make('Municipal enterprises')
                ->columns(2)
                ->components([
                    TextInput::make('enterprises_eyebrow')->maxLength(120),
                    TextInput::make('enterprises_headline')->maxLength(160),
                    Textarea::make('enterprises_lead')->rows(2)->maxLength(400)->columnSpanFull(),
                    Repeater::make('enterprises')->hiddenLabel()->columnSpanFull()
                        ->schema([
                            TextInput::make('title')->required()->maxLength(160),
                            TextInput::make('role')->maxLength(120),
                            Textarea::make('body')->required()->rows(3)->columnSpanFull(),
                        ])
                        ->columns(2)->reorderable()->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->defaultItems(0)->addActionLabel('Add enterprise'),
                ]),

            Section::make('How you can help')
                ->columns(2)
                ->components([
                    TextInput::make('help_eyebrow')->maxLength(120),
                    TextInput::make('help_headline')->maxLength(160),
                    Textarea::make('help_lead')->rows(2)->maxLength(400)->columnSpanFull(),
                    Repeater::make('help_cards')->hiddenLabel()->columnSpanFull()
                        ->schema([
                            TextInput::make('title')->required()->maxLength(120),
                            Textarea::make('description')->required()->rows(2)->columnSpanFull(),
                            TextInput::make('cta_label')->required()->maxLength(60),
                            TextInput::make('cta_url')->required()->maxLength(255),
                        ])
                        ->columns(2)->reorderable()->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->defaultItems(0)->addActionLabel('Add card'),
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
