@php
    /**
     * Site-wide SEO tags: description, canonical, Open Graph, Twitter Card and a
     * JSON-LD graph (Person + WebSite). Driven by GeneralSettings / SocialSettings
     * with optional per-page overrides via the <x-app-layout> `description` and
     * `ogImage` props.
     */
    $general = $general ?? app(\App\Settings\GeneralSettings::class);
    $social = app(\App\Settings\SocialSettings::class);

    $seoTitle = trim($title ?? '') !== '' ? $title : ($general->site_name ?: 'Talib Bensouda');
    $seoDescription = trim((string) ($description ?? '')) !== ''
        ? $description
        : ($general->site_tagline ?: 'The official campaign website of Talib Ahmed Bensouda, Lord Mayor of Kanifing Municipality, The Gambia.');
    $seoDescription = \Illuminate\Support\Str::limit(strip_tags($seoDescription), 160);

    $seoImage = trim((string) ($ogImage ?? '')) !== '' ? $ogImage : asset('images/hero-rally.webp');
    $seoUrl = url()->current();
    $siteName = $general->site_name ?: 'Talib Bensouda';

    $sameAs = array_values(array_filter([
        $social->facebook, $social->x, $social->instagram, $social->youtube,
    ]));

    $graph = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Person',
                '@id' => url('/') . '/#person',
                'name' => 'Talib Ahmed Bensouda',
                'jobTitle' => 'Lord Mayor of Kanifing Municipality',
                'url' => url('/'),
                'image' => asset('images/about-talib.webp'),
                'nationality' => 'Gambian',
            ] + ($sameAs !== [] ? ['sameAs' => $sameAs] : []),
            [
                '@type' => 'WebSite',
                '@id' => url('/') . '/#website',
                'name' => $siteName,
                'url' => url('/'),
                'inLanguage' => 'en',
                'about' => ['@id' => url('/') . '/#person'],
            ],
        ],
    ];
@endphp
<meta name="description" content="{{ $seoDescription }}">
<link rel="canonical" href="{{ $seoUrl }}">
<meta name="robots" content="max-image-preview:large">

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:url" content="{{ $seoUrl }}">
<meta property="og:image" content="{{ $seoImage }}">
<meta property="og:locale" content="en_GB">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $seoImage }}">
@if ($social->x)
    <meta name="twitter:site" content="{{ '@' . \Illuminate\Support\Str::afterLast(rtrim($social->x, '/'), '/') }}">
@endif

<script type="application/ld+json">{!! json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
