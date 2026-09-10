<?php

namespace App\Listeners;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Resend\Laravel\Events\EmailBounced;
use Resend\Laravel\Events\EmailComplained;
use Resend\Laravel\Events\EmailDeliveryDelayed;
use Resend\Laravel\Events\EmailFailed;

/**
 * Records outbound-mail problems reported by Resend webhooks so a failing
 * mailer is visible in the logs (and, in turn, to the ops-notification
 * channel) rather than silent. Registered in AppServiceProvider.
 */
class LogResendDeliveryIssue
{
    public function handle(EmailBounced|EmailComplained|EmailFailed|EmailDeliveryDelayed $event): void
    {
        $data = Arr::get($event->payload, 'data', []);
        $type = Arr::get($event->payload, 'type', 'email.unknown');

        $context = [
            'to' => Arr::get($data, 'to'),
            'subject' => Arr::get($data, 'subject'),
            'email_id' => Arr::get($data, 'email_id'),
            'reason' => Arr::get($data, 'bounce.message')
                ?? Arr::get($data, 'reason')
                ?? Arr::get($data, 'failed.reason'),
        ];

        // A delivery delay is a warning; a bounce/complaint/failure is an error.
        $level = $event instanceof EmailDeliveryDelayed ? 'warning' : 'error';

        Log::log($level, "Resend: {$type}", array_filter($context, fn ($v) => $v !== null));
    }
}
