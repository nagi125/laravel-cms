<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    // DateTime型に変換
    protected $casts = [
        'public_date' => 'datetime',
    ];

    //
    protected $guarded = ['id', 'created_at', 'updated_at'];

}
