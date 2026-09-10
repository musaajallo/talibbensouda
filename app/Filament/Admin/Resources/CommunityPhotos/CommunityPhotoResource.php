<?php

namespace App\Filament\Admin\Resources\CommunityPhotos;

use App\Filament\Admin\Resources\CommunityPhotos\Pages\CreateCommunityPhoto;
use App\Filament\Admin\Resources\CommunityPhotos\Pages\EditCommunityPhoto;
use App\Filament\Admin\Resources\CommunityPhotos\Pages\ListCommunityPhotos;
use App\Filament\Admin\Resources\CommunityPhotos\Schemas\CommunityPhotoForm;
use App\Filament\Admin\Resources\CommunityPhotos\Tables\CommunityPhotosTable;
use App\Models\CommunityPhoto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CommunityPhotoResource extends Resource
{
    protected static ?string $model = CommunityPhoto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Community photos';

    protected static ?int $navigationSort = 50;

    protected static ?string $recordTitleAttribute = 'caption';

    public static function form(Schema $schema): Schema
    {
        return CommunityPhotoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommunityPhotosTable::configure($table);
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
            'index' => ListCommunityPhotos::route('/'),
            'create' => CreateCommunityPhoto::route('/create'),
            'edit' => EditCommunityPhoto::route('/{record}/edit'),
        ];
    }
}
