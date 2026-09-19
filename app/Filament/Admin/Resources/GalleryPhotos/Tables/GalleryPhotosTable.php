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
                // Only videos can be featured. State AND icon are both conditional:
                // a badge with an icon set renders even when its text is empty, which
                // put a star on every photo.
                TextColumn::make('featured_on_home')
                    ->label('Home page')
                    ->badge()
                    ->color('warning')
                    ->state(fn (GalleryPhoto $record): ?string => $record->isVideo() && $record->featured_on_home ? 'Featured' : null)
                    ->icon(fn (GalleryPhoto $record): ?string => $record->isVideo() && $record->featured_on_home ? 'heroicon-s-star' : null),
                IconColumn::make('published')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Photos and YouTube videos share this table (see GalleryPhoto's docblock);
                // this narrows to one kind. It combines with the category tabs and with
                // the Published filter.
                SelectFilter::make('type')
                    ->label('Media type')
                    ->options([
                        GalleryPhoto::TYPE_PHOTO => 'Photos',
                        GalleryPhoto::TYPE_VIDEO => 'YouTube videos',
                    ])
                    ->placeholder('All media'),
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
