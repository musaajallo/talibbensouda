<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 — Server Error · Talib Bensouda</title>
    @vite(['resources/sass/frontend/frontend.scss'])
</head>
<body>

<div class="error-page">

    <div class="error-bar">
        <div class="error-bar__inner">
            <a href="{{ url('/') }}" class="error-bar__brand">Talib Bensouda</a>
            <span class="error-bar__status">Error 500</span>
        </div>
    </div>

    <div class="error-body">
        <div class="error-code">500</div>
        <div class="error-divider"></div>
        <h1 class="error-title">Something Went Wrong</h1>
        <p class="error-desc">
            Our team has been notified and is working to resolve the issue.
            Please try again in a few minutes.
        </p>
        <div class="error-actions">
            <a href="{{ url('/') }}" class="btn btn--gold btn--lg">Back to Home</a>
            <a href="javascript:location.reload()" class="btn btn--ghost-light">Try Again</a>
        </div>
    </div>

    <div class="error-footer">
        &copy; {{ date('Y') }} Talib Bensouda. All rights reserved.
    </div>

</div>

</body>
</html>
