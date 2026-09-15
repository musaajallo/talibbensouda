<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Concerns\NormalisesSettingsData;
use App\Settings\GalleryPageSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageGalleryPage extends SettingsPage
{
    use NormalisesSettingsData;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string $settings = GalleryPageSettings::class;

    protected static ?string $navigationLabel = 'Gallery page';

    protected static ?string $title = 'Gallery page content';

    protected static string|UnitEnum|null $navigationGroup = 'Page content';

    protected static ?int $navigationSort = 60;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pagination')
                ->description('How many photos show on one page before "Next" appears.')
                ->components([
                    TextInput::make('photos_per_page')
                        ->label('Photos per page')
                        ->numeric()
                        ->integer()
                        ->minValue(1)
                        ->maxValue(200)
                        ->required(),
                ]),
        ]);
    }
}
