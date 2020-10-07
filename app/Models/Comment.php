<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];


    protected $with = [
        'comments'
    ];

    // un commentaire appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ce modèle aura des modèle parents, par conséquent, je lui indique que j'aurais besoin d'une relation polymorphique
    public function commentable()
    {
        //ce modèle aura plusieurs modèles parents à savoir Project et Comment (un commentaire peut être la réponse à un commentaire parent)
        return $this->morphTo();
    }

    // un commentaire parent eut être commenté plusieurs fois
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function getPublishDate(){

        $commentCreationDate = \Carbon\Carbon::parse($this->created_at)->locale('fr');
        $transformCommentCreationDate = $commentCreationDate->isoFormat('D MMM YYYY à HH:mm');

        return $transformCommentCreationDate;
    }
}
