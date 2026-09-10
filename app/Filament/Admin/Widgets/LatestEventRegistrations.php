<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\EventRegistrations\EventRegistrationResource;
use App\Models\EventRegistration;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestEventRegistrations extends TableWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Latest event sign-ups')
            ->query(EventRegistration::query()->latest())
            ->defaultPaginationPageOption(5)
            ->paginated([5])
            ->recordUrl(fn (EventRegistration $record): string => EventRegistrationResource::getUrl('view', ['record' => $record]))
            ->emptyStateHeading('No sign-ups yet')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Registered')
                    ->since()
                    ->tooltip(fn (EventRegistration $record): string => $record->created_at->format('j M Y · H:i')),
                TextColumn::make('name')
                    ->weight('medium'),
                TextColumn::make('event')
                    ->limit(40),
                TextColumn::make('guests')
                    ->alignCenter(),
            ]);
    }
}
