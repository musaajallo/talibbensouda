<x-admin-layout>
    <x-slot name="header"><h1>Contract templates</h1></x-slot>

    <div class="card">
        <p>{{-- TODO: build the templates list (table of $templates) --}}</p>
        <a href="{{ route('admin.contract-templates.create') }}" class="btn btn--primary">New template</a>
    </div>
</x-admin-layout>
