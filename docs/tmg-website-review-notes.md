# TMG website review — notes (2026-09-18)

Source: `TMG Gambia - Website Review - September 2026.pdf` (gitignored — marked
CONFIDENTIAL, internal memo from campaign consultancy TMG to Babucarr Manka,
2026-09-17). Read in full before writing this.

## What the memo actually recommends

TMG's memo proposes rebuilding the site as a five-page campaign funnel —
`HOME | TALIB'S STORY | TALIB'S PLAN | MEET TALIB | HELP TALIB` — replacing the
current record-of-delivery site entirely, with an entry pop-up for data
capture, a "Talib vs. Barrow" attack section, and specific policy planks
(death penalty reinstatement, solar power for reliable electricity, cost of
living). It also flags: no consistent single brand colour yet, no data capture
happening anywhere on the site, and a suggestion to acquire `Talib2026.gm`.

**Per direction from the site owner: we are not adopting this structure.**
The site stays organised as it is (About / The People's Mayor / Events /
Giving Back / Contact, with the record of delivery as the spine), because
replacing "record of delivery" with a pure attack-and-pledges funnel is a
messaging and IA decision for the campaign to make deliberately, not something
to fold in as a side effect of a website review. The candidacy section already
added to the homepage (`home_page.candidacy_*`, PR #55/#56) is the bridge
between the two framings that currently exists.

## What's genuinely useful, and fits the current architecture

These are UX/functionality ideas from the memo that don't require adopting its
navigation or its messaging, and are additive to what's already built:

1. **Event RSVP affordances** — the memo's "I'M COMING | GET DIRECTIONS | ADD
   TO CALENDAR" pattern. The events pages already have "Add to calendar"
   (`Event::icsUrl()`/`gcalUrl()`/`outlookUrl()`) and a registration form
   (`/events/register`); a "Get directions" link (a maps URL built from
   `location`/`venue`) is the one clearly missing piece and is cheap to add.
2. **A single supporter database** — the memo's "record the source, purpose
   and consent associated with each sign-up; keep volunteer requests, event
   registrations and donations distinct." The contact/event-registration
   inboxes already do this (separate `ContactMessage`/`EventRegistration`
   models, admin notifications) — it's already the right shape, just narrower
   in scope than a full CRM.
3. **One consistent brand colour** — a real, standing issue independent of
   this memo (navy/red rebrand shipped in PR #55, but the memo's flagged
   inconsistency is worth a design pass before print/social assets diverge
   further).
4. **Build for mobile, avoid heavy animation, compress media** — already this
   site's baseline (see Performance section of the top-level `CLAUDE.md`).

## Deliberately not implemented without sign-off

Left out because each is a real-world decision about campaign messaging,
money, or legal exposure — not a code change to make unilaterally:

- **Any policy-position copy** (death penalty, blackouts/electricity framing,
  cost-of-living attack lines) — these are the candidate's actual public
  positions on a live political site; they need the campaign's explicit
  wording and sign-off, not text lifted from a strategist's draft brief.
- **Entry pop-up for data capture** and a standalone **volunteer sign-up
  form** — straightforward to build, but they create a new persistent
  supporter list; want confirmation on what's collected, retention, and who
  it notifies before adding another public form (the existing
  `throttle:public-forms` + `AdminAlert` pattern would carry over).
- **Donation flow** — the memo itself says "the treasurer and legal team must
  approve donor eligibility checks, required declarations and arrangements
  for donations from abroad before launch." Not something to scaffold ahead
  of that.
- **`Talib2026.gm` short URL** — a domain purchase/registration + redirect
  decision, not a code change.

## What this session did add

The "Upcoming Events" section now on the homepage (below The People's Mayor —
see top-level `CLAUDE.md`) is the one piece of this review that maps directly
onto an explicit ask (see the itinerary image seeded into `EventSeeder`, and
`docs/assets/campaign-itinerary/`), and required no structural or messaging
decision to build.
