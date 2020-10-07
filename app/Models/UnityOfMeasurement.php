<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnityOfMeasurement extends Model
{

    protected $table = 'unities_of_measurement';

    public function getDuration()
    {
        return $this->belongsToMany(Duration::class);
    }

    public function projects(){
        return $this->belongsToMany(Project::class);
    }
}
