<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Callback extends Model
{
    use HasFactory;

    protected $fillable = [
        'transactionOperationStatus',
        'totalAmountCharged',
        'msisdn',
        'aocTransID',
        'clientCorrelator',
        'chargeMode',
        'expiryDate',
        'subscriptionID',
        'errorCode',
        'errorMessage',
    ];

    public function callbackRaw()
    {
        return $this->hasOne(CallbackRaw::class);
    }
}
