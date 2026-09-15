<?php

namespace App\Mail;

use App\Models\Testimonial;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestimonialInviteMail extends Mailable implements ShouldQueue
{
    use Queueable;
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
