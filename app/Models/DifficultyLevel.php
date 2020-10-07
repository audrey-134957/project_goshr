<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DifficultyLevel extends Model
{

    protected $table = 'difficulty_levels';


    protected $guarded = [];
    

    // un niveau de difficulté ( facile / moyen / difficile ) appartient à un projet
    public function projects(){

        return $this->belongsToMany(Project::class);
    }
}
