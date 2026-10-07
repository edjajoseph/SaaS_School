<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class AccountActivationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
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
    public function toMail($notifiable): MailMessage
    {
        // Génération d'un lien signé sécurisé valable 48 heures
        $activationUrl = URL::temporarySignedRoute(
            'account.activate',
            now()->addHours(48),
            ['user' => $notifiable->id]
        );

        return (new MailMessage)
            ->subject('Activation de votre compte utilisateur')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Un compte utilisateur a été créé pour vous sur la plateforme.')
            ->line('Pour activer votre compte et choisir votre mot de passe, veuillez cliquer sur le bouton ci-dessous :')
            ->action('Activer mon compte', $activationUrl)
            ->line('Ce lien d\'activation expirera dans 48 heures.')
            ->line('Si vous n\'avez pas demandé ce compte, aucune action n\'est requise.');
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
