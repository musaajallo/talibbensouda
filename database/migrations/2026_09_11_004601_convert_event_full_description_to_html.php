<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * `full_description` is now a rich-text (HTML) field. Wrap any existing
 * blank-line-separated plain text in <p> tags so it renders correctly and opens
 * cleanly in the editor. Idempotent — rows that already contain markup are left
 * alone.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('events')
            ->whereNotNull('full_description')
            ->where('full_description', '!=', '')
            ->orderBy('id')
            ->each(function (object $event): void {
                if (Str::contains($event->full_description, '<')) {
                    return;
                }

                $html = collect(preg_split('/\n{2,}/', trim($event->full_description)))
                    ->map(fn (string $p): string => trim($p))
                    ->filter()
                    ->map(fn (string $p): string => '<p>'.e($p).'</p>')
                    ->implode('');

                DB::table('events')->where('id', $event->id)->update(['full_description' => $html]);
            });
    }

    public function down(): void
    {
        // One-way content transform — no safe automatic reverse.
    }
};
