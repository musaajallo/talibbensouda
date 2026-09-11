<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Settings for the Events section. For now just the list of event "types" (the
 * pill shown on each event card / page); the admin picks one per event.
 */
class EventsPageSettings extends Settings
{
    /** Flat list of type labels; the first is the default for new events. */
    public array $event_types = [];

    public static function group(): string
    {
        return 'events_page';
    }

    /**
     * Type labels in order, e.g. ['Kanifing' => 'Kanifing', 'Campaign' => 'Campaign'].
     *
     * @return array<string, string>
     */
    public function typeOptions(): array
    {
        $labels = collect($this->event_types)
            ->map(fn ($l) => trim((string) $l))
            ->filter()
            ->values();

        if ($labels->isEmpty()) {
            $labels = collect(['Kanifing', 'Campaign']);
        }

        return $labels->mapWithKeys(fn (string $l): array => [$l => $l])->all();
    }

    public function defaultType(): string
    {
        return (string) array_key_first($this->typeOptions());
    }
}
