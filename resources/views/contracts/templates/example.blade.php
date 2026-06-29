@extends('contracts.layout')

@section('content')
    <p>This Agreement is entered into between <strong>{{ config('app.name') }}</strong>
       (the "Provider") and <strong>{{ $vars['client_name'] ?? '[CLIENT]' }}</strong>
       (the "Client") on {{ now()->format('F j, Y') }}.</p>

    <h2>1. Scope</h2>
    <p>{{ $vars['scope'] ?? 'Describe the scope of work here.' }}</p>

    <h2>2. Fees</h2>
    <p>Total: <strong>{{ $vars['currency'] ?? 'GMD' }} {{ number_format($vars['amount'] ?? 0) }}</strong></p>

    <h2>3. Term</h2>
    <p>This Agreement starts on {{ $vars['start_date'] ?? '[START_DATE]' }} and ends on
       {{ $vars['end_date'] ?? '[END_DATE]' }}.</p>
@endsection
