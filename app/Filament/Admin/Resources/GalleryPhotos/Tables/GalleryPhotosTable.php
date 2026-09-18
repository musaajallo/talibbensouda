<?php

namespace App\Filament\Admin\Resources\GalleryPhotos\Tables;

use App\Models\GalleryPhoto;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
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
                // The uploaded photo, or a YouTube entry's thumbnail — a video row
                // has no media of its own, so a media-library column left it blank.
                ImageColumn::make('thumbnail')
                    ->state(fn (GalleryPhoto $record): ?string => $record->thumbnailUrl())
                    ->label('')
                    ->imageWidth(80)
                    ->imageHeight(56),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === GalleryPhoto::TYPE_VIDEO ? 'YouTube' : 'Photo')
                    ->color(fn (string $state): string => $state === GalleryPhoto::TYPE_VIDEO ? 'danger' : 'gray')
                    ->sortable(),
                TextColumn::make('caption')
                    ->placeholder('—')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                IconColumn::make('published')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
