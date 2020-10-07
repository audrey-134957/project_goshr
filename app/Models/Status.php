<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

    protected $guarded = [];

    
    // un statut ( publié / brouillon ) peut faire référence à plusieurs projets.
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
