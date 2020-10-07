<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Project;
use App\Models\User;

class NewCommentReplyPosted extends Notification
{
    use Queueable;

    protected $commentReply;
    protected $project;
    protected $user;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $commentReply,Project $project, User $user)
    {
        //je dois passer au constructeur ( donc à chaque nouvel instance de notification ) le projet et l'utilisateur qui a posté le commentaire
        $this->commentReply = $commentReply;
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
            ->subject('On a répondu à ton commentaire!')
            ->markdown('mails.new-comment-reply-posted', ['user' => $notifiable, 'commentReply' => $this->commentReply, 'project' => $this->project]);
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
            //ici, je viens déclarer ce dont j'ai besoin pour la notification dont...            //le titre du projet
            'projectTitle' => $this->project->title,
            //l'identifiant du projet
            'projectId' => $this->project->id,
            //le pseudonyme de l'utilisateur
            'userUsername' => $this->user->username,
            //id de la réponse au commentaire
            'commentReplyId' => $this->commentReply->id
        ];
    }
}
