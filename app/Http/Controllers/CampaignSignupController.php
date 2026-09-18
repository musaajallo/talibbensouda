<?php

namespace App\Http\Controllers;

use App\Models\CampaignSignup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CampaignSignupController extends Controller
{
    /**
     * Backs the site-wide sign-up pop-up, submittable from any page — a
     * plain form POST redirecting back to wherever the visitor was, rather
     * than a fixed route, since there's no one "signup page" this belongs to.
     * Field names are prefixed (popup_*) and errors use a named bag so this
     * doesn't collide with another form's `name`/`phone` fields on the same
     * page (e.g. the contact form).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('campaignSignup', [
            'popup_name' => ['required', 'string', 'max:150'],
            'popup_phone' => ['required', 'string', 'max:30'],
            'popup_location' => ['nullable', 'string', 'max:150'],
            'popup_wants_updates' => ['nullable', 'boolean'],
        ], [], [
            // Public-facing wording — without these the errors read
            // "The popup name field is required."
            'popup_name' => 'full name',
            'popup_phone' => 'phone number',
            'popup_location' => 'town or area',
        ]);

        CampaignSignup::create([
            'name' => $validated['popup_name'],
            'phone' => $validated['popup_phone'],
            'location' => $validated['popup_location'] ?? null,
            'wants_updates' => (bool) ($validated['popup_wants_updates'] ?? false),
        ]);

        return redirect()
            ->back()
            ->with('campaign_signup_success', "Thanks, {$validated['popup_name']} — you're in. We'll be in touch.");
    }
}
