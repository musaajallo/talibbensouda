<?php

namespace App\Http\Controllers;

use App\Filament\Admin\Resources\Testimonials\TestimonialResource;
use App\Models\Testimonial;
use App\Support\Notifications\AdminAlert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TestimonialSubmissionController extends Controller
{
    public function show(string $token)
    {
        $testimonial = Testimonial::where('invite_token', $token)->firstOrFail();

        return view('testimonials.submit', ['testimonial' => $testimonial]);
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $testimonial = Testimonial::where('invite_token', $token)->firstOrFail();

        if (! $testimonial->canBeEditedBySubmitter()) {
            return redirect()
                ->route('testimonials.submit', $token)
                ->with('submission_locked', 'This has already been reviewed, so it can no longer be edited.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'role' => ['nullable', 'string', 'max:160'],
            'quote' => ['required', 'string', 'max:2000'],
        ]);

        $isFirstSubmission = ! $testimonial->hasBeenSubmitted();

        $testimonial->update(array_merge($validated, ['submitted_at' => now()]));

        if ($isFirstSubmission) {
            AdminAlert::send(
                title: 'New testimonial submitted',
                body: $testimonial->name.' has submitted their testimonial for review.',
                icon: 'heroicon-o-chat-bubble-bottom-center-text',
                url: TestimonialResource::getUrl('view', ['record' => $testimonial]),
            );
        }

        return redirect()
            ->route('testimonials.submit', $token)
            ->with('submission_success', 'Thank you — your testimonial has been submitted and is awaiting review. You can still come back and edit it until then.');
    }
}
