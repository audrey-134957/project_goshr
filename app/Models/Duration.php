<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Duration extends Model
{
    // une durée a une unité de mesure ( 2 (*durée) min. (*minute))
    public function level()
    {
        return $this->hasOne(UnityOfMeasurement::class, 'unity_of_measurement_id', 'level');
    }
}
