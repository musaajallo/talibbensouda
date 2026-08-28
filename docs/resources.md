# Project Resources

Sources for content, media, and assets used to build and improve the Talib Bensouda website.

## Links

| Resource | URL | Notes |
| --- | --- | --- |
| Kanifing Municipal Council — Facebook (search) | https://www.facebook.com/search/top?q=kanifing%20municipal%20council | Official KMC activity, project updates, press coverage, photos |
| Mayor Talib Bensouda — Facebook page | https://www.facebook.com/MayorBensouda | Primary source for statements, events, photos, and video |
| Google Drive — shared folder | https://drive.google.com/drive/folders/1_ZSusslq7aGXpRPoc10vD8OgL5b7oqji | Campaign / project media assets (images, documents, logos) |

## Local assets

Working assets live in [`docs/assets/`](./assets/). This folder is **git-ignored**
(raw source media, too heavy for the repo) — only files copied into `public/` ship.

- `market-event.mp4` — event footage
- `Talib Legacy - The People's Mayor.docx` — legacy / biography content (Markdown copy at [`docs/Talib Legacy - The People's Mayor.md`](./Talib%20Legacy%20-%20The%20People's%20Mayor.md))
- `Coalition_2026_Logo.pdf` — Coalition 2026 logo
- `coalition-flagbearer/` — 56 original photos, Coalition 2026 flagbearer announcement (2026-08-10)
- `mou-signing-ceremony/` — 127 original photos, MOU signing ceremony (2026-08-08); includes 8 `.HEIC`
- `coalition-flagbearer-webp/` — 54 compressed WebP (16 MB → 5 MB), duplicates removed
- `mou-signing-ceremony-webp/` — 125 compressed WebP (51 MB → 18 MB), HEIC converted, duplicates removed

### Homepage hero slider

Live images in `public/images/` (referenced from `resources/views/welcome.blade.php`):

| File | Source | Subject |
| --- | --- | --- |
| `hero-talib-desk.webp` | umc project | Talib at his desk (portrait) |
| `hero-masquerade.webp` | mou-signing `48b69441` | Kankurang / masquerade with crowd + flag |
| `hero-supporters.webp` | mou-signing `b30bcf42` | Talib greeting supporters under the Gambian flag |
| `hero-hall.webp` | mou-signing `img_2650` | Packed convention hall |
| `hero-victory.webp` | coalition-flagbearer `4b42fc2a` | Raised-hands endorsement moment |

Conversion helper: `docs/convert-images.php` (run `php docs/convert-images.php <srcDir> <destDir> [maxEdge] [quality]`).

## Content status

All public pages were rewritten from placeholder copy to sourced content from
`Talib Legacy - The People's Mayor.md` on 2026-08-28. Decisions applied:

- **Framing:** non-partisan — record of his work as Lord Mayor of Kanifing, not a
  party-recruitment site. "Join Party" CTAs and `unitemovementgambia.com` links
  removed; his UDP → UNITE Movement for Change trajectory kept as factual context.
- **Events:** the fictional "Diaspora Tour 2026" (London/NY/Madrid) was removed
  from `EventSeeder`, `events.blade.php` and `events/register.blade.php`. Replaced
  with six sourced KMC milestones (2022–2026). `events/register` is now a general
  "register your interest" form.
- **Figures:** invented stats replaced with documented ones (revenue D115M→D332M,
  police 42→200, 38 km roads, 31,867 digital addresses, etc.). Unsourced numbers
  (e.g. "100K party members", "50K lives impacted") dropped. A self-reporting
  caveat is shown on the People's Mayor page.
- **Testimonials:** fabricated quotes/names removed. Home page "What People Are
  Saying" became a Recognition section (New Castle County declarations, Global
  Parliament of Mayors, Dubawa fact-check).

### Still needs real input (not in the source doc)

- Photos for every page (placeholders remain), and the featured video
- Real contact email (currently placeholder `info@talibbensouda.gm`), phone, office address
- Real social media URLs (only Facebook `/MayorBensouda` is wired; X, Instagram,
  YouTube, WhatsApp still `#`)
- Any genuine upcoming events / calendar
- Beneficiary quotes if testimonial-style content is wanted back
- `/feed` route returns 404 (spatie/laravel-feed stub) — unrelated pre-existing issue
