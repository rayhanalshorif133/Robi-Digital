<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetAOCTokenResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'get_aoc_token_id',
        'aocToken',
        'aocTransID',
        'errorCode',
        'errorMessage',
    ];

    public function getAOCToken(){
        return $this->belongsTo(GetAOCToken::class);
    }
}
