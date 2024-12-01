<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargeStatusResponseRaw extends Model
{
    use HasFactory;

    protected $fillable = [
        'charge_status_response_id',
        'data',
    ];

    public function chargeStatusResponse()
    {
        return $this->belongsTo(ChargeStatusResponse::class);
    }
}
