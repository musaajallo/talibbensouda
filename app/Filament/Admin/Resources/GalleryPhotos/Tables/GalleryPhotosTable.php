<?php

namespace App\Filament\Admin\Resources\GalleryPhotos\Tables;

use App\Models\GalleryPhoto;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class GalleryPhotosTable
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
                    ->imageWidth(80)
                    ->imageHeight(56),
                TextColumn::make('caption')
                    ->placeholder('—')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                IconColumn::make('wide')
                    ->label('Wide')
                    ->boolean(),
                IconColumn::make('published')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options(array_combine(GalleryPhoto::CATEGORIES, GalleryPhoto::CATEGORIES)),
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
