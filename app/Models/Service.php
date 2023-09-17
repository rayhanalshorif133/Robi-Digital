<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'name',
        'type',
        'keyword',
        'validity',
        'purchase_category_code',
        'reference_code',
        'channel',
        'on_behalf_of',
    ];
}
