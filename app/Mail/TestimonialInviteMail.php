<?php

namespace App\Mail;

use App\Models\Testimonial;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Sent synchronously (not ShouldQueue) — this is a low-volume, admin-triggered
 * send, and the panel action needs the real send/fail result immediately so
 * a delivery failure surfaces to the admin right away instead of failing
 * silently in a queue worker nobody's watching.
 */
class TestimonialInviteMail extends Mailable
{
    use SerializesModels;

    public function __construct(public Testimonial $testimonial) {}

    public function build(): self
    {
        return $this
            ->subject('Share a few words for Talib Bensouda')
            ->markdown('emails.testimonial-invite', [
                'name' => $this->testimonial->invite_name,
                'url' => $this->testimonial->submissionUrl(),
            ]);
    }
}
