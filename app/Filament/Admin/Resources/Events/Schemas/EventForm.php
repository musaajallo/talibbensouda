<?php

namespace App\Filament\Admin\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
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
                ->description('The headline, the URL slug and where it shows on the site.')
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
                        ->label('Type')
                        ->options([
                            'gambia' => 'Kanifing',
                            'diaspora' => 'Campaign',
                        ])
                        ->default('gambia')
                        ->required()
                        ->native(false),
                    TextInput::make('flag')
                        ->maxLength(8)
                        ->default('🇬🇲')
                        ->helperText('Emoji shown next to the date.'),
                    Toggle::make('is_upcoming')
                        ->label('Show in the current milestones list')
                        ->helperText('Turn off once the event has passed.')
                        ->default(true),
                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->required()
                        ->helperText('Lower numbers appear first.'),
                ]),

            Section::make('Date & place')
                ->description('One date and time — the site copy, the calendar grid and the "add to calendar" export all derive from it.')
                ->columns(2)
                ->components([
                    DateTimePicker::make('starts_at')
                        ->label('Starts')
                        ->required()
                        ->native(false)
                        ->seconds(false)
                        ->minutesStep(15)
                        ->displayFormat('D j M Y · H:i'),
                    DateTimePicker::make('ends_at')
                        ->label('Ends')
                        ->required()
                        ->native(false)
                        ->seconds(false)
                        ->minutesStep(15)
                        ->displayFormat('D j M Y · H:i')
                        ->afterOrEqual('starts_at'),
                    TextInput::make('location')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Town or area, e.g. "Bakau".'),
                    TextInput::make('venue')
                        ->maxLength(255)
                        ->helperText('Optional — a specific building or hall.'),
                ]),

            Section::make('Description')
                ->components([
                    Textarea::make('description')
                        ->required()
                        ->rows(2)
                        ->maxLength(500)
                        ->helperText('One or two sentences for the list and cards.'),
                    Textarea::make('full_description')
                        ->rows(8)
                        ->helperText('Shown on the event page. Separate paragraphs with a blank line.'),
                ]),
        ]);
    }
}
