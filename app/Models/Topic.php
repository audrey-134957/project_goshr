<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $guarded = [];



    protected $with = [
        'topics'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($topic) {
            $topic->user_id = auth()->user()->id;
        });

        self::updating(function ($topic) {
            $topic->user_id = auth()->user()->id;
        });
    }

    // un commentaire appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ce modèle aura des modèle parents, par conséquent, je lui indique que j'aurais besoin d'une relation polymorphique
    public function topicable()
    {
        //ce modèle aura plusieurs modèles parents à savoir Project et Topic (un topic peut être la réponse à un topic parent)
        return $this->morphTo();
    }

    // un topic parent peut être commenté plusieurs fois
    public function topics()
    {
        return $this->morphMany(Topic::class, 'topicable');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function getPublishDate(){

        $topicCreationDate = \Carbon\Carbon::parse($this->created_at)->locale('fr');
        $transformTopicCreationDate = $topicCreationDate->isoFormat('D MMM YYYY à HH:mm');

        return $transformTopicCreationDate;
    }
}
