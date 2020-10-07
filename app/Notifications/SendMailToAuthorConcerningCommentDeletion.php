<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendMailToAuthorConcerningCommentDeletion extends Notification
{
    use Queueable;

    protected $commment;

    protected $project;

    protected $commentAuthor;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $comment,Project $project, User $commentAuthor)
    {
        $this->comment = $comment;
        $this->project = $project;
        $this->commentAuthor = $commentAuthor;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->subject('Suppression de votre commentaire')
            ->markdown('mails.comment-deleted', [
                'comment' => $this->comment,
                'project' => $this->project,
                'commentAuthor' => $notifiable
            ]);
    }
}
