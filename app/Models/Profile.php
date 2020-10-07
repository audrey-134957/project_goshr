<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Storage;


class Profile extends Model
{

    protected $fillable = [
        'biography',
        'user_id',
    ];

    //un profile appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
