<?php

namespace App\Filament\Admin\Resources\Milestones\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class MilestoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(160)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, $state, callable $set): void {
                            if ($operation === 'create') {
                                $set('slug', Str::slug((string) $state));
                            }
                        })
                        ->columnSpanFull(),
                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('A stable key — not used in a public URL, milestones have no detail page.'),
                    DatePicker::make('occurred_on')->required()->native(false),
                    TextInput::make('location')->maxLength(160)->columnSpanFull(),
                    Textarea::make('description')->required()->rows(3)->maxLength(600)->columnSpanFull(),
                    Toggle::make('published')
                        ->default(true)
                        ->helperText('Controls whether it shows on the home page and the events page.')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
