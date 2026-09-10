<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Concerns\NormalisesSettingsData;
use App\Settings\AboutPageSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageAboutPage extends SettingsPage
{
    use NormalisesSettingsData;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static string $settings = AboutPageSettings::class;

    protected static ?string $navigationLabel = 'About page';

    protected static ?string $title = 'About page content';

    protected static string|UnitEnum|null $navigationGroup = 'Page content';

    protected static ?int $navigationSort = 40;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hero')
                ->columns(2)
                ->components([
                    TextInput::make('hero_eyebrow')->maxLength(120),
                    TextInput::make('hero_title')->maxLength(160),
                    Textarea::make('hero_subtitle')->rows(2)->maxLength(400)->columnSpanFull()
                        ->helperText('A newline breaks the subtitle across two lines.'),
                ]),

            Section::make('Biography')
                ->columns(2)
                ->components([
                    TextInput::make('bio_eyebrow')->maxLength(120),
                    TextInput::make('bio_headline')->maxLength(160),
                    Textarea::make('bio_body')->rows(10)->columnSpanFull()
                        ->helperText('Separate paragraphs with a blank line.'),
                    FileUpload::make('bio_photo')->image()->imageEditor()
                        ->disk('public')->directory('about')->visibility('public')->maxSize(6144)
                        ->helperText('Portrait shown beside the biography.'),
                ]),

            Section::make('Timeline')
                ->columns(2)
                ->components([
                    TextInput::make('timeline_eyebrow')->maxLength(120),
                    TextInput::make('timeline_headline')->maxLength(160),
                    Textarea::make('timeline_lead')->rows(2)->maxLength(400)->columnSpanFull(),
                    Repeater::make('timeline')->hiddenLabel()->columnSpanFull()
                        ->schema([
                            TextInput::make('year')->required()->maxLength(12),
                            TextInput::make('title')->required()->maxLength(160),
                            Textarea::make('description')->required()->rows(2)->columnSpanFull(),
                        ])
                        ->columns(2)->reorderable()->collapsible()
                        ->itemLabel(fn (array $state): string => trim(($state['year'] ?? '').' — '.($state['title'] ?? '')))
                        ->defaultItems(0)->addActionLabel('Add milestone'),
                ]),

            Section::make('Four priorities')
                ->columns(2)
                ->components([
                    TextInput::make('values_eyebrow')->maxLength(120),
                    TextInput::make('values_headline')->maxLength(160),
                    Textarea::make('values_lead')->rows(2)->maxLength(400)->columnSpanFull(),
                    Repeater::make('values')->hiddenLabel()->columnSpanFull()
                        ->schema([
                            TextInput::make('title')->required()->maxLength(120),
                            Textarea::make('description')->required()->rows(3)->columnSpanFull(),
                        ])
                        ->reorderable()->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->defaultItems(0)->maxItems(6)->addActionLabel('Add priority'),
                ]),

            Section::make('National platform')
                ->columns(2)
                ->components([
                    TextInput::make('national_eyebrow')->maxLength(120),
                    TextInput::make('national_headline')->maxLength(160),
                    Textarea::make('national_body')->rows(6)->columnSpanFull()
                        ->helperText('Separate paragraphs with a blank line.'),
                    Repeater::make('national_pillars')->label('Pillars')->columnSpanFull()
                        ->simple(TextInput::make('pillar')->required()->maxLength(160))
                        ->reorderable()->defaultItems(0)->addActionLabel('Add pillar'),
                    FileUpload::make('national_logo')->image()->imageEditor()
                        ->disk('public')->directory('about')->visibility('public')->maxSize(4096),
                    TextInput::make('national_logo_name')->maxLength(120),
                    TextInput::make('national_logo_caption')->maxLength(160),
                    TextInput::make('national_cta_label')->maxLength(60),
                    TextInput::make('national_cta_url')->maxLength(255),
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
