<?php

namespace App\Filament\Admin\Resources\Testimonials\Tables;

use App\Models\Testimonial;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('role')->placeholder('—')->limit(40),
                TextColumn::make('quote')->limit(60)->wrap()->placeholder('—'),
                TextColumn::make('statusLabel')
                    ->label('Status')
                    ->state(fn (Testimonial $record) => $record->statusLabel())
                    ->badge()
                    ->color(fn (Testimonial $record): string => match (true) {
                        $record->invite_token === null => 'gray',
                        ! $record->hasBeenSubmitted() => 'gray',
                        ! $record->approved => 'warning',
                        $record->published => 'success',
                        default => 'info',
                    }),
                IconColumn::make('featured')->boolean(),
                IconColumn::make('published')->boolean()->sortable(),
                TextColumn::make('sort_order')->label('Sort')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('published'),
                Filter::make('awaiting_approval')
                    ->label('Awaiting approval')
                    ->query(fn ($query) => $query->awaitingApproval())
                    ->toggle(),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Testimonial $record): bool => $record->hasBeenSubmitted() && ! $record->approved)
                    ->requiresConfirmation()
                    ->action(function (Testimonial $record): void {
                        $record->update(['approved' => true]);

                        Notification::make()
                            ->title('Testimonial approved')
                            ->body('It still needs to be published separately to go live.')
                            ->success()
                            ->send();
                    }),
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
