<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    // DateTime型に変換
    protected $dates = ['public_date'];

    //
    protected $guarded = ['id', 'created_at', 'updated_at'];

}
