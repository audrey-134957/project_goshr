<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{

    //un role peut appartenir à plusieurs utilisateurs
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
