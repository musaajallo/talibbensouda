<?php

namespace App\Models;

use App\Observers\ContactMessageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(ContactMessageObserver::class)]
class ContactMessage extends Model
{
    protected $fillable = [
        'type', 'name', 'email', 'phone', 'subject', 'country', 'message',
    ];
}
