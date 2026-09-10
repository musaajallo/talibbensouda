<?php

namespace App\Filament\Admin\Pages\Concerns;

use ReflectionClass;
use ReflectionNamedType;

/**
 * Filament's `TextInput` / `Textarea` dehydrate an empty field to `null`, but the
 * settings classes type most properties as a non-nullable `string` (or `array`).
 * Assigning `null` to those throws a `TypeError` inside laravel-settings and the
 * save 500s. This trait coerces every `null` back to the zero value for that
 * property's declared type before the settings object is filled — so clearing an
 * optional field just stores an empty string instead of blowing up.
 */
trait NormalisesSettingsData
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $reflection = new ReflectionClass(static::getSettings());

        foreach ($data as $key => $value) {
            if ($value !== null || ! $reflection->hasProperty($key)) {
                continue;
            }

            $type = $reflection->getProperty($key)->getType();

            if (! $type instanceof ReflectionNamedType || $type->allowsNull()) {
                continue;
            }

            $data[$key] = match ($type->getName()) {
                'string' => '',
                'array' => [],
                'int' => 0,
                'float' => 0.0,
                'bool' => false,
                default => $value,
            };
        }

        return $data;
    }
}
