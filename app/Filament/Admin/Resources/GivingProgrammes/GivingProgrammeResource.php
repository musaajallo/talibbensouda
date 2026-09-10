<?php

namespace App\Filament\Admin\Resources\GivingProgrammes;

use App\Filament\Admin\Resources\GivingProgrammes\Pages\CreateGivingProgramme;
use App\Filament\Admin\Resources\GivingProgrammes\Pages\EditGivingProgramme;
use App\Filament\Admin\Resources\GivingProgrammes\Pages\ListGivingProgrammes;
use App\Filament\Admin\Resources\GivingProgrammes\Schemas\GivingProgrammeForm;
use App\Filament\Admin\Resources\GivingProgrammes\Tables\GivingProgrammesTable;
use App\Models\GivingProgramme;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GivingProgrammeResource extends Resource
{
    protected static ?string $model = GivingProgramme::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Giving programmes';

    protected static ?int $navigationSort = 60;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return GivingProgrammeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GivingProgrammesTable::configure($table);
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
            'index' => ListGivingProgrammes::route('/'),
            'create' => CreateGivingProgramme::route('/create'),
            'edit' => EditGivingProgramme::route('/{record}/edit'),
        ];
    }
}
