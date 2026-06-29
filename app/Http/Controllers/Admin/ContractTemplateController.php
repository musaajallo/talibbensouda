<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContractTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractTemplateController extends Controller
{
    public function index(): View
    {
        return view('admin.contract-templates.index', [
            'templates' => ContractTemplate::query()->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.contract-templates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $template = ContractTemplate::create($data);

        return redirect()->route('admin.contract-templates.show', $template)
            ->with('status', 'Template created.');
    }

    public function show(ContractTemplate $contractTemplate): View
    {
        return view('admin.contract-templates.show', ['template' => $contractTemplate]);
    }

    public function edit(ContractTemplate $contractTemplate): View
    {
        return view('admin.contract-templates.edit', ['template' => $contractTemplate]);
    }

    public function update(Request $request, ContractTemplate $contractTemplate): RedirectResponse
    {
        $contractTemplate->update($this->validateData($request));

        return redirect()->route('admin.contract-templates.show', $contractTemplate)
            ->with('status', 'Template updated.');
    }

    public function destroy(ContractTemplate $contractTemplate): RedirectResponse
    {
        $contractTemplate->delete();

        return redirect()->route('admin.contract-templates.index')
            ->with('status', 'Template deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'blade_view' => ['required', 'string', 'max:255'],
            'default_variables' => ['nullable', 'array'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
