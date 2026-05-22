<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Joke extends Model
{
    protected $fillable = [
        'api_id', 'type', 'setup', 'punchline', 'joke', 'raw_data'
    ];

    protected $casts = [
        'raw_data' => 'array',
        'created_at' => 'datetime'
    ];
}
