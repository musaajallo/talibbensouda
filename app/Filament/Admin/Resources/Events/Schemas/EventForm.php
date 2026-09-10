<?php

namespace App\Filament\Admin\Resources\Events\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Milestone')
                ->columns(2)
                ->components([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, $state, callable $set): void {
                            if ($operation === 'create') {
                                $set('slug', Str::slug((string) $state));
                            }
                        }),
                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Used in the event URL: /events/<slug>'),
                    Select::make('badge')
                        ->options([
                            'gambia' => 'Kanifing',
                            'diaspora' => 'Campaign',
                        ])
                        ->default('gambia')
                        ->required(),
                    TextInput::make('flag')
                        ->maxLength(8)
                        ->default('🇬🇲')
                        ->helperText('Emoji shown next to the date.'),
                    Toggle::make('is_upcoming')
                        ->label('Show in the current milestones list')
                        ->default(true),
                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->required()
                        ->helperText('Lower numbers appear first.'),
                ]),

            Section::make('Date & place')
                ->columns(3)
                ->components([
                    TextInput::make('date_day')->label('Day (display)')->required()->maxLength(8)->placeholder('12'),
                    TextInput::make('date_month')->label('Month (display)')->required()->maxLength(20)->placeholder('July'),
                    TextInput::make('date_year')->label('Year (display)')->required()->maxLength(8)->placeholder('2025'),
                    TextInput::make('js_day')->label('Day (number)')->numeric()->minValue(1)->maxValue(31)->required(),
                    TextInput::make('js_month')->label('Month (0–11)')->numeric()->minValue(0)->maxValue(11)->required()
                        ->helperText('January = 0, December = 11.'),
                    TextInput::make('location')->required()->maxLength(255)->columnSpan(1),
                    TextInput::make('venue')->maxLength(255)->columnSpan(2),
                ]),

            Section::make('Description')
                ->components([
                    Textarea::make('description')->required()->rows(2)->maxLength(500)
                        ->helperText('One or two sentences for the list and cards.'),
                    Textarea::make('full_description')->rows(8)
                        ->helperText('Shown on the event page. Separate paragraphs with a blank line.'),
                ]),

            Section::make('Add to calendar')
                ->columns(2)
                ->collapsed()
                ->components([
                    TextInput::make('ics_start')->required()->maxLength(20)->placeholder('20250712T090000Z')
                        ->helperText('UTC, format YYYYMMDDThhmmssZ.'),
                    TextInput::make('ics_end')->required()->maxLength(20)->placeholder('20250712T110000Z'),
                ]),
        ]);
    }
}
