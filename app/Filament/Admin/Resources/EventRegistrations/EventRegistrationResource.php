<?php

namespace App\Filament\Admin\Resources\EventRegistrations;

use App\Filament\Admin\Resources\EventRegistrations\Pages\ListEventRegistrations;
use App\Filament\Admin\Resources\EventRegistrations\Pages\ViewEventRegistration;
use App\Filament\Admin\Resources\EventRegistrations\Tables\EventRegistrationsTable;
use App\Models\EventRegistration;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EventRegistrationResource extends Resource
{
    protected static ?string $model = EventRegistration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static string|UnitEnum|null $navigationGroup = 'Submissions';

    protected static ?string $navigationLabel = 'Event registrations';

    protected static ?int $navigationSort = 20;

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
                    TextEntry::make('event'),
                    TextEntry::make('name'),
                    TextEntry::make('email')->copyable(),
                    TextEntry::make('phone')->placeholder('—'),
                    TextEntry::make('guests'),
                    TextEntry::make('requirements')->placeholder('—')->columnSpanFull()->prose(),
                    TextEntry::make('message')->placeholder('—')->columnSpanFull()->prose(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return EventRegistrationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEventRegistrations::route('/'),
            'view' => ViewEventRegistration::route('/{record}'),
        ];
    }
}
