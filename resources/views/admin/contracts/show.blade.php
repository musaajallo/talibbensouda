<x-admin-layout>
    <x-slot name="header"><h1>{{ $contract->title }}</h1></x-slot>

    <div class="card">
        <p>Reference: <strong>{{ $contract->reference }}</strong></p>
        <p>Status: {{ $contract->status }}</p>
        <p>
            <a href="{{ route('admin.contracts.preview', $contract) }}" target="_blank" class="btn btn--secondary">Preview HTML</a>
            <a href="{{ route('admin.contracts.pdf', $contract) }}" class="btn btn--primary">Download PDF</a>
        </p>
    </div>
</x-admin-layout>
