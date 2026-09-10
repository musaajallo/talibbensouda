<?php

namespace App\Filament\Admin\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('badge')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'diaspora' ? 'Campaign' : 'Kanifing'),
                TextColumn::make('date_month')
                    ->label('Date')
                    ->formatStateUsing(fn ($record): string => "{$record->date_day} {$record->date_month} {$record->date_year}"),
                TextColumn::make('location')
                    ->searchable()
                    ->limit(40),
                IconColumn::make('is_upcoming')
                    ->label('Current')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Sort')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('badge')
                    ->options([
                        'gambia' => 'Kanifing',
                        'diaspora' => 'Campaign',
                    ]),
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
