<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Rank extends Model
{
    protected $fillable = [
        'name',
        'slug'
    ];

    protected static function boot()
    {
        parent::boot();

        self::saving(function ($rank) {
            $rank->slug = Str::slug($rank->name);
        });
    }

}
