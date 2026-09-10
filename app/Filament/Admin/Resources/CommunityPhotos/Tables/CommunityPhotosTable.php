<?php

namespace App\Filament\Admin\Resources\CommunityPhotos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CommunityPhotosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                SpatieMediaLibraryImageColumn::make('photo')
                    ->collection('photo')
                    ->label('')
                    ->imageWidth(72)
                    ->imageHeight(54),
                TextColumn::make('tag')->badge()->placeholder('—'),
                TextColumn::make('caption')->searchable()->wrap(),
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
