<?php

namespace App\Filament\Admin\Resources\Events\Schemas;

use App\Models\Event;
use Carbon\CarbonImmutable;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Event details')
                ->columns(2)
                ->components([
                    TextEntry::make('title'),
                    TextEntry::make('slug'),
                    TextEntry::make('badge')->label('Type')->badge(),
                    IconEntry::make('is_upcoming')
                        ->label('Published')
                        ->boolean(),
                ]),

            Section::make('Date & place')
                ->columns(2)
                ->components([
                    TextEntry::make('ics_start')
                        ->label('Date & time')
                        ->formatStateUsing(function (?string $state, Event $record): string {
                            if (blank($state)) {
                                return '—';
                            }

                            $start = CarbonImmutable::createFromFormat('Ymd\THis\Z', $state, 'UTC');
                            $end = filled($record->ics_end)
                                ? CarbonImmutable::createFromFormat('Ymd\THis\Z', $record->ics_end, 'UTC')
                                : null;

                            return $start->format('D j M Y, H:i').($end ? ' – '.$end->format('H:i') : '');
                        }),
                    TextEntry::make('location'),
                    TextEntry::make('venue')->placeholder('—'),
                ]),

            Section::make('Description')
                ->components([
                    TextEntry::make('description')->label('Summary'),
                    TextEntry::make('full_description')
                        ->label('Full write-up')
                        ->html()
                        ->prose()
                        ->placeholder('—'),
                ]),

            Section::make('Flyer')
                ->components([
                    SpatieMediaLibraryImageEntry::make('flyer')
                        ->collection('flyer')
                        ->hiddenLabel()
                        ->imageHeight(320)
                        ->placeholder('No flyer uploaded — a branded placeholder is shown on the site instead.'),
                ]),
        ]);
    }
}
