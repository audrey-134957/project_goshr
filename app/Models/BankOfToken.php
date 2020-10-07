<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BankOfToken extends Model
{
    protected $table = 'banks_of_tokens';

    protected $fillable = [
        'token_project',
        'token_project_draft',
    ];


    // un commentaire appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
