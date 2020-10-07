<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendConfirmationMailToAdmin extends Notification
{
    use Queueable;

    public $admin;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $admin)
    {
        //je récupère le pseudo de l'utilisateur pour le stocker dans la variable $admin
        $this->admin = $admin;
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
        ->subject('Ton compte est validé !')
        ->markdown('mails.admin-account-confirmed', ['admin' => $notifiable]);
    }
}
