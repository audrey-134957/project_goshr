<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Material extends Model
{

    protected $fillable = [
        'name',
        'project_id'
    ];

    // un materiel appartient  à un utilisateur.
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
