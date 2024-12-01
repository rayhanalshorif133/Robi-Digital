<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetAOCTokenLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'get_aoc_token_id',
        'get_aoc_token_response_id',
        'request_raw_data',
        'response_raw_data',
    ];


    public function getAOCToken()
    {
        return $this->belongsTo(GetAOCToken::class);
    }

    public function getAOCTokenResponse()
    {
        return $this->belongsTo(GetAOCTokenResponse::class);
    }
}
