<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderInfo extends Model
{
    use HasFactory;

    protected $table = 'service_provider_infos';

    protected $fillable = [
        'aoc_endpoint_url',
        'aoc_redirection_url',
        'aoc_getAOCToken_url',
        'sp_username',
        'sp_api_key',
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
    ];



}
