@php
    $general = app(\App\Settings\GeneralSettings::class);
    $siteName = $general->site_name ?: config('app.name');
    // The public site never uses an image logo — just a text wordmark, same
    // fallback convention as the admin panel's own brand (resources/views/
    // filament/admin/brand.blade.php): an admin-uploaded logo wins if set.
    $uploadedLogo = $general->uploadedLogoUrl();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteName }} — Be right back</title>
    <link rel="icon" type="image/x-icon" href="{{ $general->faviconUrl() }}">
    <style>
        :root { color-scheme: light; }
        html, body {
            margin: 0;
            font-family: "Inter", "Helvetica Neue", Arial, sans-serif;
            background: #0B142E;
            color: #fff;
            height: 100%;
        }
        .maintenance {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 20px;
            text-align: center;
        }
        .maintenance__panel { max-width: 520px; }
        .maintenance__logo { height: 56px; width: auto; margin-bottom: 32px; }
        .maintenance__eyebrow {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #BD2038;
            margin-bottom: 16px;
        }
        .maintenance__title {
            font-size: 2.2rem;
            font-weight: 800;
            margin: 0 0 16px;
            line-height: 1.2;
        }
        .maintenance__message {
            margin: 0 0 28px;
            font-size: 1.05rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.75);
        }
        .maintenance__contact {
            display: inline-block;
            padding: 12px 22px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 999px;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
        }
        .maintenance__contact:hover { border-color: #BD2038; color: #BD2038; }
        .maintenance__logo { height: 56px; width: auto; margin-bottom: 32px; }
        .maintenance__wordmark {
            display: block;
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: 0.01em;
            margin-bottom: 32px;
        }
    </style>
</head>
<body>
    <main class="maintenance" aria-labelledby="maintenance-heading">
        <div class="maintenance__panel">
            @if ($uploadedLogo)
                <img class="maintenance__logo" src="{{ $uploadedLogo }}" alt="{{ $siteName }}">
            @else
                <span class="maintenance__wordmark">{{ $siteName }}</span>
            @endif
            <p class="maintenance__eyebrow">Coming Soon</p>
            <h1 id="maintenance-heading" class="maintenance__title">We'll be right back.</h1>
            <p class="maintenance__message">
                The site is temporarily down for maintenance. We'll be back online shortly —
                thank you for your patience.
            </p>
            <a class="maintenance__contact" href="mailto:{{ $general->contact_email }}">{{ $general->contact_email }}</a>
        </div>
    </main>
</body>
</html>
