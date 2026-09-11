<?php

namespace App\Filament\Admin\Resources\Events\Schemas;

use App\Settings\EventsPageSettings;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        $settings = app(EventsPageSettings::class);

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
                        ->options($settings->typeOptions())
                        ->default($settings->defaultType())
                        ->required()
                        ->native(false)
                        ->helperText('Manage the list under Page content → Events page.'),
                    Toggle::make('is_upcoming')
                        ->label('Show in the current milestones list')
                        ->helperText('Turn off once the event has passed.')
                        ->default(true),
                ]),

            Section::make('Date & place')
                ->description('Everything on the site — the date card, the calendar grid and the "add to calendar" export — comes from this.')
                ->columns(2)
                ->components([
                    DatePicker::make('event_date')
                        ->label('Date')
                        ->required()
                        ->native(false)
                        ->displayFormat('D j M Y')
                        ->closeOnDateSelection()
                        ->columnSpanFull(),
                    TimePicker::make('start_time')
                        ->label('Start time')
                        ->seconds(false)
                        ->minutesStep(15)
                        ->default('09:00'),
                    TimePicker::make('end_time')
                        ->label('End time')
                        ->seconds(false)
                        ->minutesStep(15)
                        ->default('17:00')
                        ->after('start_time'),
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
                        ->label('Summary')
                        ->required()
                        ->rows(2)
                        ->maxLength(500)
                        ->helperText('One or two sentences for the events list and cards.'),
                    RichEditor::make('full_description')
                        ->label('Full write-up')
                        ->toolbarButtons([
                            'bold', 'italic', 'link', 'bulletList', 'orderedList', 'blockquote', 'h3', 'undo', 'redo',
                        ])
                        ->helperText('Shown on the event page. Leave blank to fall back to the summary.'),
                ]),

            Section::make('Flyer')
                ->description('Shown on the events list, the event page and when the link is shared. Optional — a branded placeholder with the title and date is used until one is uploaded.')
                ->components([
                    SpatieMediaLibraryFileUpload::make('flyer')
                        ->collection('flyer')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios(['4:5', '1:1', null])
                        ->responsiveImages()
                        ->maxSize(4096)
                        ->hiddenLabel()
                        ->helperText('Recommended 1200 × 1500px (a 4:5 portrait poster). JPG, PNG or WebP, up to 4 MB.'),
                ]),
        ]);
    }
}
