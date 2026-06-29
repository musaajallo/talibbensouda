<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Page Not Found · Talib Bensouda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/sass/frontend/frontend.scss'])
</head>
<body>

<div class="error-page">

    <div class="error-bar">
        <div class="error-bar__inner">
            <a href="{{ url('/') }}" class="error-bar__brand">Talib Bensouda</a>
            <span class="error-bar__status">Error 404</span>
        </div>
    </div>

    <div class="error-body">
        <div class="error-code">404</div>
        <div class="error-divider"></div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-desc">
            The page you're looking for doesn't exist or may have been moved.
            Double-check the URL or head back to the homepage.
        </p>
        <div class="error-actions">
            <a href="{{ url('/') }}" class="btn btn--gold btn--lg">Back to Home</a>
            <a href="{{ url('/events') }}" class="btn btn--ghost-light">View Events</a>
        </div>
    </div>

    <div class="error-footer">
        &copy; {{ date('Y') }} Talib Bensouda. All rights reserved.
    </div>

</div>

</body>
</html>
