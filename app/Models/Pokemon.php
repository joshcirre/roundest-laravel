<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $guarded = ['id'];

    public static function getRandomPair()
    {
        return self::inRandomOrder()->limit(2)->get();
    }
}
