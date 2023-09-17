<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallbackRaw extends Model
{
    use HasFactory;

    protected $fillable = [
        'callback_id',
        'data',
    ];

    public function callback()
    {
        return $this->belongsTo(Callback::class);
    }
}
