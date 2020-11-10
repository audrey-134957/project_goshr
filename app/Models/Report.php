<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Report extends Model

{

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($report) {
            // $report->user_id = auth()->user()->id;
        });

        self::saving(function ($report) {
            // $report->user_id = auth()->user()->id;
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function motives()
    {
        return $this->belongsToMany(Motive::class);
    }

    public function reportable()
    {

        return $this->morphTo();
    }


    //je vais récupérer grâce à cette fonction la route du signalement selon son type
    public function reportRoute()
    {
        if ($this->reportable_type === 'App\Models\Comment') {
            $route = route('admin.showCommentReport', [
                'adminId' => auth()->user()->id,
                'report' => $this->id,
                'comment' => $this->reportable->id
            ]);
        } else if ($this->reportable_type === 'App\Models\Topic') {
            $route = route('admin.showTopicReport', [
                'adminId' => auth()->user()->id,
                'report' => $this->id,
                'topic' => $this->reportable->id
            ]);
        } else {
            $route = route('admin.showProjectReport', [
                'adminId' => auth()->user()->id,
                'report' => $this->id,
                'project' => $this->reportable->slug
            ]);
        }


        return $route;
    }

    //je vais récupérer grâce à cette fonction l'avatar de l'auteur du signalement
    public function reportAuthorAvatar()
    {
        $reportAuthorAvatar =  $this->user->getImage($this->user);

        return $reportAuthorAvatar;
    }

    //je vais récupérer grâce à cette fonction le contenu custom du signalement
    public function content()
    {
        if ($this->reportable_type === 'App\Models\Comment') {

            $contentType = 'commentaire ' . '"' . $this->reportable->content . '"';
        } elseif ($this->reportable_type === 'App\Models\Topic') {

            $contentType = 'topic ' . '"' . $this->reportable->content . '"';
        } else {

            $contentType = 'projet ' . '"' . $this->reportable->title . '"';
        }

        $notificationDate = \Carbon\Carbon::parse($this->created_at)->locale('fr');
        $transformNotificationDate = $notificationDate->isoFormat('D MMM YYYY à HH:mm');

        $reportAuthor = $this->user->username;

        $html = '<p style="margin: auto 0;color:gray;">';
        $html .= '<span style="color:black;">' . $reportAuthor . '</span>';
        $html .= ' a signalé le ' . $contentType . ' le ' . $transformNotificationDate . ' pour les motifs suivants';
        $html .= '</p>';

        return $html;
    }
}
