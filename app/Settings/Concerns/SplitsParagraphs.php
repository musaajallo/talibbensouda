<?php

namespace App\Settings\Concerns;

trait SplitsParagraphs
{
    /**
     * Split a blank-line-separated string property into trimmed paragraphs.
     *
     * @return array<int, string>
     */
    public function paragraphs(string $property): array
    {
        return collect(preg_split('/\R{2,}/', (string) $this->{$property}))
            ->map(fn (string $p): string => trim($p))
            ->filter()
            ->values()
            ->all();
    }
}
