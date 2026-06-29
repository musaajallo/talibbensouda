<x-admin-layout>
    <x-slot name="header"><h1>Contracts</h1></x-slot>

    <div class="card">
        <p>{{-- TODO: build the contracts list (table of $contracts) --}}</p>
        <a href="{{ route('admin.contracts.create') }}" class="btn btn--primary">New contract</a>
    </div>
</x-admin-layout>
