<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewSubscription extends Model
{
    use HasFactory;

    protected $table = 'renew_subscriptions';

    protected $fillable = [
        'msisdn',
        'old_spTransID',
        'new_spTransID',
        'sent_raw_parameter',
        'subscription_id',
        'url',
        'amount',
        'keyword',
        'response_code',
        'response',
        'response_data',
        'response_message',
    ];
}
