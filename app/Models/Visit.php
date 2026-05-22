<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'visitor_id', 'ip', 'city', 'country', 'device_type',
        'browser', 'os', 'page_url', 'referrer'
    ];
    
    protected $casts = [
        'created_at' => 'datetime'
    ];
}
