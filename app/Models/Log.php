<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = ['level', 'context', 'message', 'extra'];

    protected $casts = [
        'extra' => 'array',
    ];
}
