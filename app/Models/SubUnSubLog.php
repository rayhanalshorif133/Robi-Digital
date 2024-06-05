<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubUnSubLog extends Model
{
    use HasFactory;

    protected $table = 'sub_un_sub_logs';

    protected $fillable = [
        'msisdn',
        'keyword',
        'status',
        'subscriptionID',
        'flag',
        'opt_date',
        'opt_time',
    ];


}
