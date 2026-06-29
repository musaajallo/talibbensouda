<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Down for Maintenance · Talib Bensouda</title>
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
            <span class="error-bar__status">Maintenance</span>
        </div>
    </div>

    <div class="error-body">
        <div class="error-code" style="font-size: clamp(4rem, 14vw, 9rem);">🛠</div>
        <div class="error-divider"></div>
        <h1 class="error-title">Down for Maintenance</h1>
        <p class="error-desc">
            We're making improvements to the site. We'll be back shortly.
            Follow us on social media for real-time updates from the campaign.
        </p>
        <div class="error-actions">
            <a href="javascript:location.reload()" class="btn btn--gold btn--lg">Try Again</a>
        </div>
    </div>

    <div class="error-footer">
        &copy; {{ date('Y') }} Talib Bensouda. All rights reserved.
    </div>

</div>

</body>
</html>
