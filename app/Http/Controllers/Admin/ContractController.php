<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractTemplate;
use App\Services\ContractRenderer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ContractController extends Controller
{
    public function index(): View
    {
        return view('admin.contracts.index', [
            'contracts' => Contract::query()->with('template')->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.contracts.create', [
            'templates' => ContractTemplate::query()->where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['reference'] ??= 'C-'.strtoupper(Str::random(8));
        $data['created_by'] = $request->user()->id;

        $contract = Contract::create($data);

        return redirect()->route('admin.contracts.show', $contract)
            ->with('status', 'Contract created.');
    }

    public function show(Contract $contract): View
    {
        return view('admin.contracts.show', ['contract' => $contract->load('template', 'creator')]);
    }

    public function edit(Contract $contract): View
    {
        return view('admin.contracts.edit', [
            'contract' => $contract,
            'templates' => ContractTemplate::query()->where('is_active', true)->get(),
        ]);
    }

    public function update(Request $request, Contract $contract): RedirectResponse
    {
        $contract->update($this->validateData($request));

        return redirect()->route('admin.contracts.show', $contract)
            ->with('status', 'Contract updated.');
    }

    public function destroy(Contract $contract): RedirectResponse
    {
        $contract->delete();

        return redirect()->route('admin.contracts.index')->with('status', 'Contract deleted.');
    }

    public function preview(Contract $contract, ContractRenderer $renderer): HttpResponse
    {
        return response($renderer->html($contract));
    }

    public function pdf(Contract $contract, ContractRenderer $renderer): \Spatie\LaravelPdf\PdfBuilder
    {
        return $renderer->pdf($contract);
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'contract_template_id' => ['required', 'exists:contract_templates,id'],
            'reference' => ['nullable', 'string', 'max:64'],
            'title' => ['required', 'string', 'max:255'],
            'variables' => ['required', 'array'],
            'status' => ['required', 'in:draft,sent,signed,void'],
            'counterparty_type' => ['nullable', 'string'],
            'counterparty_id' => ['nullable', 'integer'],
        ]);
    }
}
