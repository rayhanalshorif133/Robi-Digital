<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargeStatusResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'aocTransID',
        'chargeMode',
        'transactionOperationStatus',
        'totalAmountCharged',
        'clientCorrelator',
        'msisdn',
        'errorCode',
        'errorMessage',
    ];
}
