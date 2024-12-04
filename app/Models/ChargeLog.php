<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargeLog extends Model
{
    use HasFactory;

    protected $table = 'charge_logs';
    
    protected $fillable = [
        'spTransID',
        'msisdn',
        'keyword',
        'amount',
        'type',
        'charge_date',
    ];

}
