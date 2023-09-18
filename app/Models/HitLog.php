<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HitLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'get_aoc_token_id',
        'keyword',
        'postBack_send_data',
        'time',
        'date',
    ];

    public $timestamps = false;

    public function getAOCToken()
    {
        return $this->belongsTo(GetAOCToken::class, 'get_aoc_token_id');
    }
}
