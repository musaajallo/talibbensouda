<?php

namespace App\Filament\Admin\Resources\Events\Tables;

use App\Models\Event;
use App\Settings\EventsPageSettings;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('ics_start', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('flyer')
                    ->collection('flyer')
                    ->label('')
                    ->imageWidth(56)
                    ->imageHeight(70)
                    ->defaultImageUrl(fn (Event $record): string => $record->flyerImageUrl()),
                TextColumn::make('starts_at')
                    ->label('Date')
                    ->dateTime('j M Y · H:i')
                    ->placeholder('—')
                    ->sortable(query: fn ($query, string $direction) => $query->orderBy('ics_start', $direction)),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('badge')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('location')
                    ->searchable()
                    ->limit(40),
                IconColumn::make('is_upcoming')
                    ->label('Current')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('badge')
                    ->label('Type')
                    ->options(fn (): array => app(EventsPageSettings::class)->typeOptions()),
                TernaryFilter::make('is_upcoming')
                    ->label('Current milestones'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
