<?php

namespace App\Filament\Admin\Resources\Testimonials\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    TextEntry::make('quote')->columnSpanFull()->prose(),
                    TextEntry::make('name'),
                    TextEntry::make('role')->placeholder('—'),
                    IconEntry::make('featured')->boolean(),
                    IconEntry::make('published')->boolean(),
                    TextEntry::make('sort_order')->label('Sort order'),
                ]),
        ]);
    }
}
