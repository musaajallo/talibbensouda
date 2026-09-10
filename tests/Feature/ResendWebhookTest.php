<?php

use App\Listeners\LogResendDeliveryIssue;
use Illuminate\Support\Facades\Log;
use Resend\Laravel\Events\EmailBounced;
use Resend\Laravel\Events\EmailDelivered;

use function Pest\Laravel\postJson;

function bouncePayload(): array
{
    return [
        'type' => 'email.bounced',
        'data' => [
            'email_id' => 'e-123',
            'to' => ['someone@example.com'],
            'subject' => 'Reset your password',
            'bounce' => ['message' => 'Mailbox does not exist'],
        ],
    ];
}

it('logs a Resend bounce with the recipient and reason', function (): void {
    Log::spy();

    (new LogResendDeliveryIssue)->handle(new EmailBounced(bouncePayload()));

    Log::shouldHaveReceived('log')->once()->withArgs(function ($level, $message, $context) {
        return $level === 'error'
            && str_contains($message, 'email.bounced')
            && $context['to'] === ['someone@example.com']
            && $context['reason'] === 'Mailbox does not exist';
    });
});

it('accepts the Resend webhook and fires the delivery-issue listener', function (): void {
    Log::spy();

    postJson('/resend/webhook', bouncePayload())->assertOk();

    Log::shouldHaveReceived('log')->withArgs(fn ($level) => $level === 'error');
});

it('does not log successful deliveries', function (): void {
    Log::spy();

    // EmailDelivered isn't in the listener's event list — nothing should log.
    event(new EmailDelivered(['type' => 'email.delivered', 'data' => []]));

    Log::shouldNotHaveReceived('log');
});
