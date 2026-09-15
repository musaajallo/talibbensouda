<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property string $quote
 * @property string $name
 * @property string|null $role
 * @property bool $featured
 * @property bool $published
 * @property bool $approved
 * @property int $sort_order
 * @property string|null $invite_token
 * @property string|null $invite_name
 * @property string|null $invite_email
 * @property Carbon|null $invite_sent_at
 * @property Carbon|null $submitted_at
 */
class Testimonial extends Model
{
    use LogsActivity;

    protected $fillable = [
        'key', 'quote', 'name', 'role', 'featured', 'published', 'approved', 'sort_order',
        'invite_token', 'invite_name', 'invite_email', 'invite_sent_at', 'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'published' => 'boolean',
            'approved' => 'boolean',
            'sort_order' => 'integer',
            'invite_sent_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'featured', 'published', 'approved', 'sort_order'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    /** Rows created by emailing someone a submission link, awaiting their first reply. */
    public function scopeAwaitingSubmission(Builder $query): Builder
    {
        return $query->whereNotNull('invite_token')->whereNull('submitted_at');
    }

    /** Submitted via a link and not yet reviewed. */
    public function scopeAwaitingApproval(Builder $query): Builder
    {
        return $query->whereNotNull('submitted_at')->where('approved', false);
    }

    /** Create a draft row and its unique submission link, ready to email out. */
    public static function createInvite(string $name, string $email): self
    {
        return static::create([
            'invite_token' => Str::random(48),
            'invite_name' => $name,
            'invite_email' => $email,
            'invite_sent_at' => now(),
            'name' => $name,
            'quote' => '',
            'published' => false,
            'approved' => false,
        ]);
    }

    /**
     * The submitter can keep editing their own testimonial right up until an
     * admin approves it — after that the content is locked in.
     */
    public function canBeEditedBySubmitter(): bool
    {
        return ! $this->approved;
    }

    public function hasBeenSubmitted(): bool
    {
        return $this->submitted_at !== null;
    }

    public function submissionUrl(): ?string
    {
        return $this->invite_token ? route('testimonials.submit', $this->invite_token) : null;
    }

    /** Short status label for the admin table/infolist. */
    public function statusLabel(): string
    {
        if ($this->invite_token === null) {
            return $this->published ? 'Live' : 'Draft';
        }

        if (! $this->hasBeenSubmitted()) {
            return 'Invited';
        }

        if (! $this->approved) {
            return 'Pending review';
        }

        return $this->published ? 'Live' : 'Approved';
    }
}
