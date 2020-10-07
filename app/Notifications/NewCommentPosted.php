<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Project;
use App\Models\User;

class NewCommentPosted extends Notification
{
    use Queueable;
    /**
     * The user instance.
     * @var Comment;
     * @var Project
     * @var User
     */
    protected $comment;
    protected $project;
    protected $user;


    /**
     * Create a new notification instance.
     * @param int $project
     * @param string $user
     * @return void
     */
    public function __construct(Comment $comment, Project $project, User $user)
    {
        //je dois passer au constructeur ( donc à chaque nouvel instance de notification ) le projet et l'utilisateur qui a posté le commentaire
        $this->comment = $comment;
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
        //commme on souhaite sauvegarder la notification en BDD, je viens préciser ici que je veux utiliser la BDD.
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
            ->subject('Un commentaire a été laissé sur ton projet.')
            ->markdown('mails.new-comment-posted', ['user' => $notifiable, 'project' => $this->project, 'comment' => $this->comment]);
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
            //id du commentaire
            'commentId' => $this->comment->id
        ];
    }
}
