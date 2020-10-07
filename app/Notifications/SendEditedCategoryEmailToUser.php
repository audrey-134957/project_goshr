<?php

namespace App\Notifications;

use App\Models\Category;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEditedCategoryEmailToUser extends Notification
{
    use Queueable;

    /**
     * The category instance.
     *
     * @var Category
     * @var User
     */
    protected $category;
    protected $user;


    /**
     * Create a new notification instance.
     *
     * @param string $category
     * @param string $user
     * @return void
     */
    public function __construct(Category $category, User $user)
    {
        //je récupère le pseudo de la catégorie pour le stocker dans la variable $catégorie.
        $this->category = $category;
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
            ->subject("Du nouveau dans les categories")
            ->markdown('mails.validation.show');
    }
}
