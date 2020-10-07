<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


use App\Models\Project;
use App\Models\Topic;
use App\Models\User;

class NewTopicPosted extends Notification
{
    use Queueable;

    protected $topic;
    protected $project;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Project $project,Topic $topic,User $user)
    {
        //je dois passer au constructeur ( donc à chaque nouvel instance de notification ) le projet et l'utilisateur qui a posté le commentaire
        $this->project = $project;
        $this->topic = $topic;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Une question a été posée pour ton projet.')
            ->markdown('mails.new-topic-posted', (['user' => $notifiable, 'topic' => $this->topic, 'project' => $this->project]));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
          //ici, je viens déclarer ce dont j'ai besoin pour la notification dont...
            //le titre du projet
            'projectTitle' => $this->project->title,
            //l'identifiant du projet
            'projectId' => $this->project->id,
            //le pseudonyme de l'utilisateur
            'userUsername' => $this->user->username,
            //id du topic
            'topicId' => $this->topic->id
        ];
    }
}
