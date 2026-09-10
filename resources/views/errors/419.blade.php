<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>419 — Session Expired · Talib Bensouda</title>
    @vite(['resources/sass/frontend/frontend.scss'])
</head>
<body>

<div class="error-page">

    <div class="error-bar">
        <div class="error-bar__inner">
            <a href="{{ url('/') }}" class="error-bar__brand">Talib Bensouda</a>
            <span class="error-bar__status">Error 419</span>
        </div>
    </div>

    <div class="error-body">
        <div class="error-code">419</div>
        <div class="error-divider"></div>
        <h1 class="error-title">Session Expired</h1>
        <p class="error-desc">
            Your session has expired for security reasons.
            Please go back and try again — your form may need to be resubmitted.
        </p>
        <div class="error-actions">
            <a href="javascript:history.back()" class="btn btn--gold btn--lg">Go Back</a>
            <a href="{{ url('/') }}" class="btn btn--ghost-light">Back to Home</a>
        </div>
    </div>

    <div class="error-footer">
        &copy; {{ date('Y') }} Talib Bensouda. All rights reserved.
    </div>

</div>

</body>
</html>
