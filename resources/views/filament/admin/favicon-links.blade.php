{{--
    Panel::favicon() only ever renders a single <link rel="icon" href="...">
    (see vendor/filament/filament/src/Panel/Concerns/HasFavicon.php) — passing
    it favicon.ico alone means modern browsers, which prefer an SVG icon over
    an .ico when both are declared, show a crisper icon on the public site
    than in the panel even though both point at the same navy/red mark. These
    extra tags match resources/views/layouts/app.blade.php exactly, so the
    two are rendered identically rather than merely using the same colours.
--}}
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
