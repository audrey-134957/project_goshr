<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Motive extends Model
{

    protected $fillable = [];

    public static function boot()
    {
        parent::boot();

     
        self::saving(function ($motive) {
            $motive->slug = Str::slug($motive->name);
        });
    }

    public function reports(){
        return $this->belongsToMany(Report::class);
    }
}
