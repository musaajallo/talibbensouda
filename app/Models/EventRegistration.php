<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
