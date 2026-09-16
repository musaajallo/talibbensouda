<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->deleteIfExists('peoples_mayor_page.figures_note');
    }

    public function down(): void
    {
        $this->migrator->add('peoples_mayor_page.figures_note', 'Figures are drawn from Kanifing Municipal Council records and public reporting. Council-reported financial figures for 2018–2022 are self-reported; several KMC market, road and library projects were independently verified by a Dubawa fact-check in December 2025.');
    }
};
