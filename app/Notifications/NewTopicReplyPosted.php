<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Topic;
use App\Models\Project;
use App\Models\User;

class NewTopicReplyPosted extends Notification
{
    use Queueable;

    protected $topicReply;
    protected $project;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Topic $topicReply, Project $project, User $user)
    {
        //je dois passer au constructeur ( donc à chaque nouvel instance de notification ) le projet et l'utilisateur qui a posté le commentaire
        $this->topicReply = $topicReply;
        $this->project = $project;
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
        return ['mail', 'database'];
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
            ->subject('Une réponse a été apporté à la question.')
            ->markdown('mails.new-topic-reply-posted', ['user' => $notifiable, 'topicReply' => $this->topicReply, 'project' => $this->project]);
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
            //le pseudonyme de l'utilisateur
            'userUsername' => $this->user->username,
            //l'identifiant du projet
            'projectId' => $this->project->id,
            //le titre du projet
            'projectTitle' => $this->project->title,
            //id de la réponse au topic
            'topicReplyId' => $this->topicReply->id

        ];
    }
}
