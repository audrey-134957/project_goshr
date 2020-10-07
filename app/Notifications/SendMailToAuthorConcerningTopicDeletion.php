<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendMailToAuthorConcerningTopicDeletion extends Notification
{
    use Queueable;

    protected $topic;

    protected $project;

    protected $topicAuthor;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Topic $topic, Project $project, User $topicAuthor)
    {
        $this->topic = $topic;
        $this->project = $project;
        $this->topicAuthor = $topicAuthor;
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
            ->subject('Votre topic a été supprimé.')
            ->markdown('mails.topic-deleted', [
                'topic' => $this->topic,
                'project' => $this->project,
                'user' => $notifiable

            ]);
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
            //
        ];
    }
}
