<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendMailToAuthorConcerningProjectDeletion extends Notification
{
    use Queueable;

    protected $project;
    protected $projectAuthor;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Project $project, User $projectAuthor)
    {
     
        $this->project = $project;
        $this->projectAuthor = $project->user;
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
            ->subject('Votre projet a été supprimé.')
            ->markdown('mails.project-deleted', [
                'project' => $this->project,
                'projectAuthor' => $notifiable
            ]);
    }


}
