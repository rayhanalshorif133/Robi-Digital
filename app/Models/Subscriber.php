<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $table = 'subscribers';

    protected $fillable = [
        'msisdn',
        'status',
        'keyword',
        'spTransID',
        'aocTransID',
        'subscriptionID',
        'subscriptionDuration',
        'subs_date',
        'unsubs_date',
        'charge',
        'flag'
    ];
}
