<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Concerns\NormalisesSettingsData;
use App\Settings\EventsPageSettings;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageEventsPage extends SettingsPage
{
    use NormalisesSettingsData;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string $settings = EventsPageSettings::class;

    protected static ?string $navigationLabel = 'Events page';

    protected static ?string $title = 'Events page content';

    protected static string|UnitEnum|null $navigationGroup = 'Page content';

    protected static ?int $navigationSort = 50;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Event types')
                ->description('The pill shown on each event card and page. Pick one per event when adding it. The first row is the default for new events.')
                ->components([
                    Repeater::make('event_types')
                        ->hiddenLabel()
                        ->simple(
                            TextInput::make('label')->required()->maxLength(40)->placeholder('e.g. Kanifing'),
                        )
                        ->reorderable()
                        ->addActionLabel('Add type')
                        ->minItems(1)
                        ->defaultItems(1),
                ]),
        ]);
    }
}
