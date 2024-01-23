<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendToAdminAfterRegistrationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $code, public $email)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Crétion du compte administrateur.')
                    ->line('Bonjour.')
                    ->line('Votre copte a été crée avec succès.')
                    ->line('Cliquez sur le bouton ci-dessous pour valider votre compte.')
                    ->line('Saississez le code '. $this->code .' et renseignez-le danss le formulaire qui apparaitra.')
                    ->action('Cliquez ici', url('/validate-account' . '/' . $this->email))
                    ->line('Merci d\'utiliser nos services!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
