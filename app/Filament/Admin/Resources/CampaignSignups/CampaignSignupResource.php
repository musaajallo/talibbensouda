<?php

namespace App\Filament\Admin\Resources\CampaignSignups;

use App\Filament\Admin\Resources\CampaignSignups\Pages\ListCampaignSignups;
use App\Filament\Admin\Resources\CampaignSignups\Pages\ViewCampaignSignup;
use App\Filament\Admin\Resources\CampaignSignups\Tables\CampaignSignupsTable;
use App\Models\CampaignSignup;
use BackedEnum;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CampaignSignupResource extends Resource
{
    protected static ?string $model = CampaignSignup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;

    protected static string|UnitEnum|null $navigationGroup = 'Submissions';

    protected static ?string $navigationLabel = 'Pop-up sign-ups';

    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::whereDate('created_at', '>=', now()->subDays(7))->count() ?: null;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('name'),
                    TextEntry::make('phone')->copyable(),
                    TextEntry::make('location')->placeholder('—'),
                    IconEntry::make('wants_updates')->label('Wants campaign updates')->boolean(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return CampaignSignupsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampaignSignups::route('/'),
            'view' => ViewCampaignSignup::route('/{record}'),
        ];
    }
}
