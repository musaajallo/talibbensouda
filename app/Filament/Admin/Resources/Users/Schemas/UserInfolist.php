<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Account')
                ->columns(2)
                ->components([
                    TextEntry::make('name'),
                    TextEntry::make('email')->label('Email address')->copyable(),
                    TextEntry::make('roles.name')
                        ->label('Roles')
                        ->badge()
                        ->placeholder('—'),
                ]),

            Section::make('Avatar')
                ->components([
                    SpatieMediaLibraryImageEntry::make('avatar')
                        ->collection('avatar')
                        ->hiddenLabel()
                        ->circular(),
                ]),
        ]);
    }
}
