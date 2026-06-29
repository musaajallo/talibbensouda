<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property int $contract_template_id
 * @property string|null $counterparty_type
 * @property int|null $counterparty_id
 * @property string $reference
 * @property string $title
 * @property array<string,mixed> $variables
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $signed_at
 * @property string|null $signed_ip
 * @property string|null $signed_signature
 * @property int|null $created_by
 * @property-read ContractTemplate $template
 * @property-read User|null $creator
 */
#[Fillable([
    'contract_template_id',
    'counterparty_type',
    'counterparty_id',
    'reference',
    'title',
    'variables',
    'status',
    'signed_at',
    'signed_ip',
    'signed_signature',
    'created_by',
])]
class Contract extends Model implements HasMedia
{
    use SoftDeletes, LogsActivity, InteractsWithMedia;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SENT = 'sent';
    public const STATUS_SIGNED = 'signed';
    public const STATUS_VOID = 'void';

    protected function casts(): array
    {
        return [
            'variables' => 'array',
            'signed_at' => 'datetime',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ContractTemplate::class, 'contract_template_id');
    }

    public function counterparty(): MorphTo
    {
        return $this->morphTo();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('rendered')->singleFile();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'reference', 'title', 'signed_at'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
