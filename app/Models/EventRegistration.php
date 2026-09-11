<?php

namespace App\Models;

use App\Observers\EventRegistrationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(EventRegistrationObserver::class)]
class EventRegistration extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'event',
        'guests',
        'requirements',
        'message',
    ];
}
