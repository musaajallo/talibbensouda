<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Account')
                ->columns(2)
                ->components([
                    TextInput::make('name')->required()->maxLength(120),
                    TextInput::make('email')
                        ->label('Email address')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(190),
                    TextInput::make('password')
                        ->password()
                        ->revealable()
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->helperText('Leave blank to keep the current password. Hashed automatically on save.')
                        ->maxLength(255),
                    Select::make('roles')
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->preload()
                        ->helperText('Users need the "admin" or "super-admin" role to sign in here.'),
                ]),

            Section::make('Avatar')
                ->components([
                    SpatieMediaLibraryFileUpload::make('avatar')
                        ->collection('avatar')
                        ->avatar()
                        ->image()
                        ->imageEditor()
                        ->circleCropper()
                        ->hiddenLabel(),
                ]),
        ]);
    }
}
