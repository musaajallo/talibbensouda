<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $add = fn (string $key, $value) => $this->migrator->add("home_page.{$key}", $value);

        $add('candidacy_eyebrow', '2026 Presidential Election');
        $add('candidacy_headline', 'Leading the UNITE Movement for Change');
        $add('candidacy_body', 'In 2026, Talib Ahmed Bensouda emerged as leader of the opposition UNITE Movement for Change, campaigning nationally on what he calls "The Transformation Agenda" — carrying the record built in Kanifing to a wider argument about how The Gambia is governed.');
        $add('candidacy_cta_label', 'Read the National Platform');
        $add('candidacy_cta_url', '/about');
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('home_page.candidacy_eyebrow');
        $this->migrator->deleteIfExists('home_page.candidacy_headline');
        $this->migrator->deleteIfExists('home_page.candidacy_body');
        $this->migrator->deleteIfExists('home_page.candidacy_cta_label');
        $this->migrator->deleteIfExists('home_page.candidacy_cta_url');
    }
};
