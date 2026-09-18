<?php

namespace App\Models;

use App\Observers\CampaignSignupObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

/**
 * Submissions from the site-wide sign-up pop-up (resources/views/components/
 * campaign-signup-popup.blade.php). Deliberately its own inbox rather than a
 * third `type` on ContactMessage — no email is collected here (name + phone
 * + optional location only), so it doesn't fit that model's required-email
 * shape.
 */
#[ObservedBy(CampaignSignupObserver::class)]
class CampaignSignup extends Model
{
    protected $fillable = ['name', 'phone', 'location', 'wants_updates'];

    protected function casts(): array
    {
        return [
            'wants_updates' => 'boolean',
        ];
    }
}
