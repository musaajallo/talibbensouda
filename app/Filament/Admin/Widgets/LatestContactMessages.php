<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestContactMessages extends TableWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Latest messages')
            ->query(ContactMessage::query()->latest())
            ->defaultPaginationPageOption(5)
            ->paginated([5])
            ->recordUrl(fn (ContactMessage $record): string => ContactMessageResource::getUrl('view', ['record' => $record]))
            ->emptyStateHeading('No messages yet')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->tooltip(fn (ContactMessage $record): string => $record->created_at->format('j M Y · H:i')),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('name')
                    ->weight('medium'),
                TextColumn::make('subject')
                    ->placeholder('—')
                    ->limit(40),
            ]);
    }
}
