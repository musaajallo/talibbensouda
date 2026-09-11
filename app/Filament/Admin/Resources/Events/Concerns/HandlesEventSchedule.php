<?php

namespace App\Filament\Admin\Resources\Events\Concerns;

use Carbon\CarbonImmutable;

/**
 * The events table stores the schedule seven ways — display strings
 * (`date_day`/`date_month`/`date_year`), 0-indexed calendar integers
 * (`js_day`/`js_month`) and UTC ICS timestamps (`ics_start`/`ics_end`). The admin
 * form only asks for one date and two times; this trait splits those columns
 * apart to fill the form and reassembles them on save. Used by CreateEvent and
 * EditEvent.
 */
trait HandlesEventSchedule
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $start = filled($data['ics_start'] ?? null)
            ? CarbonImmutable::createFromFormat('Ymd\THis\Z', $data['ics_start'], 'UTC')
            : null;
        $end = filled($data['ics_end'] ?? null)
            ? CarbonImmutable::createFromFormat('Ymd\THis\Z', $data['ics_end'], 'UTC')
            : null;

        $data['event_date'] = $start?->toDateString();
        $data['start_time'] = $start?->format('H:i');
        $data['end_time'] = $end?->format('H:i');

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->assembleSchedule($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->assembleSchedule($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function assembleSchedule(array $data): array
    {
        if (filled($data['event_date'] ?? null)) {
            $date = CarbonImmutable::parse($data['event_date'])->toDateString();
            $start = CarbonImmutable::parse($date.' '.($data['start_time'] ?: '09:00'));
            $end = CarbonImmutable::parse($date.' '.($data['end_time'] ?: '17:00'));

            $data['ics_start'] = $start->format('Ymd\THis\Z');
            $data['ics_end'] = $end->format('Ymd\THis\Z');
            $data['date_day'] = $start->format('j');
            $data['date_month'] = $start->format('M'); // "Dec" — matches the compact date card
            $data['date_year'] = $start->format('Y');
            $data['js_day'] = (int) $start->format('j');
            $data['js_month'] = (int) $start->format('n') - 1;
        }

        unset($data['event_date'], $data['start_time'], $data['end_time']);

        return $data;
    }
}
