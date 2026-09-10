<?php

namespace App\Filament\Admin\Resources\Testimonials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('role')->placeholder('—')->limit(40),
                TextColumn::make('quote')->limit(60)->wrap(),
                IconColumn::make('featured')->boolean(),
                IconColumn::make('published')->boolean()->sortable(),
                TextColumn::make('sort_order')->label('Sort')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('published'),
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
