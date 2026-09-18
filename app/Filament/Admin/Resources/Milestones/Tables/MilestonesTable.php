<?php

namespace App\Filament\Admin\Resources\Milestones\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MilestonesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('occurred_on', 'desc')
            ->columns([
                TextColumn::make('occurred_on')->date()->sortable(),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('location')->placeholder('—')->limit(40),
                IconColumn::make('published')->boolean()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('published'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
