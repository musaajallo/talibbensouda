<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $contract->reference }} — {{ $contract->title }}</title>
    <style>
        @page { size: A4; margin: 24mm 20mm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11pt; color: #1a1a1a; line-height: 1.5; }
        h1 { font-size: 18pt; margin: 0 0 4pt; }
        h2 { font-size: 13pt; margin: 16pt 0 6pt; }
        h3 { font-size: 11pt; margin: 12pt 0 4pt; }
        .meta { color: #666; font-size: 9pt; }
        .signature { margin-top: 32pt; }
        .signature__line { border-top: 1px solid #1a1a1a; padding-top: 6pt; width: 60%; }
        .footer { position: fixed; bottom: -18mm; left: 0; right: 0; text-align: center; font-size: 8pt; color: #999; }
    </style>
</head>
<body>
    <header>
        <div class="meta">{{ $contract->reference }} · drafted {{ $contract->created_at?->format('Y-m-d') }}</div>
        <h1>{{ $contract->title }}</h1>
    </header>

    <main>
        @yield('content')
    </main>

    <div class="signature">
        <div class="signature__line">
            @if($contract->signed_signature)
                <em>Signed: {{ $contract->signed_signature }}</em><br>
                <span class="meta">{{ $contract->signed_at?->format('Y-m-d H:i') }} from {{ $contract->signed_ip }}</span>
            @else
                <em>Signature line</em>
            @endif
        </div>
    </div>

    <div class="footer">{{ config('app.name') }} · {{ $contract->reference }}</div>
</body>
</html>
