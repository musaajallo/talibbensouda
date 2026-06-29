<?php

namespace App\Services;

use App\Models\Contract;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\PdfBuilder;

/**
 * Renders a Contract to HTML or PDF using its ContractTemplate's Blade view.
 *
 * The template's Blade view receives:
 *   - $contract  (App\Models\Contract)
 *   - $template  (App\Models\ContractTemplate)
 *   - $vars      (merged default + per-contract variables, array)
 *
 * Templates live in resources/views/contracts/templates/{slug}.blade.php and
 * extend a shared layout (resources/views/contracts/layout.blade.php).
 */
class ContractRenderer
{
    public function html(Contract $contract): string
    {
        $template = $contract->template;
        $vars = array_merge($template->default_variables ?? [], $contract->variables ?? []);

        return view($template->blade_view, [
            'contract' => $contract,
            'template' => $template,
            'vars' => $vars,
        ])->render();
    }

    public function pdf(Contract $contract): PdfBuilder
    {
        $html = $this->html($contract);

        return Pdf::html($html)
            ->name(sprintf('%s.pdf', $contract->reference))
            ->download();
    }

    /**
     * Render and persist the PDF to the contract's `rendered` media collection.
     */
    public function saveRendered(Contract $contract): void
    {
        $html = $this->html($contract);
        $tmpPath = tempnam(sys_get_temp_dir(), 'contract').'.pdf';

        Pdf::html($html)->save($tmpPath);

        $contract->clearMediaCollection('rendered');
        $contract->addMedia($tmpPath)
            ->usingFileName(sprintf('%s.pdf', $contract->reference))
            ->toMediaCollection('rendered');
    }
}
