<?php

namespace App\Filament\Admin\Resources\GalleryPhotos;

use App\Filament\Admin\Resources\GalleryPhotos\Pages\CreateGalleryPhoto;
use App\Filament\Admin\Resources\GalleryPhotos\Pages\EditGalleryPhoto;
use App\Filament\Admin\Resources\GalleryPhotos\Pages\ListGalleryPhotos;
use App\Filament\Admin\Resources\GalleryPhotos\Schemas\GalleryPhotoForm;
use App\Filament\Admin\Resources\GalleryPhotos\Tables\GalleryPhotosTable;
use App\Models\GalleryPhoto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GalleryPhotoResource extends Resource
{
    protected static ?string $model = GalleryPhoto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Gallery';

    protected static ?string $modelLabel = 'gallery photo';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'caption';

    public static function form(Schema $schema): Schema
    {
        return GalleryPhotoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GalleryPhotosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGalleryPhotos::route('/'),
            'create' => CreateGalleryPhoto::route('/create'),
            'edit' => EditGalleryPhoto::route('/{record}/edit'),
        ];
    }
}
