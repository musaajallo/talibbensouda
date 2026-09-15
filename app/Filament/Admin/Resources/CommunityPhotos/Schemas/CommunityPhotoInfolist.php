<?php

namespace App\Filament\Admin\Resources\CommunityPhotos\Schemas;

use App\Models\CommunityPhoto;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommunityPhotoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Photo')
                ->components([
                    SpatieMediaLibraryImageEntry::make('photo')
                        ->collection('photo')
                        ->hiddenLabel()
                        ->imageHeight(320),
                ]),

            Section::make('Details')
                ->columns(2)
                ->components([
                    TextEntry::make('group')
                        ->badge()
                        ->formatStateUsing(fn (string $state): string => CommunityPhoto::GROUPS[$state] ?? $state),
                    TextEntry::make('tag')->badge()->placeholder('—'),
                    TextEntry::make('caption')->columnSpanFull(),
                    IconEntry::make('published')->boolean(),
                    TextEntry::make('sort_order')->label('Sort order'),
                ]),
        ]);
    }
}
