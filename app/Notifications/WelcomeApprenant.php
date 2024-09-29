<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeApprenant extends Notification implements ShouldQueue
{
    use Queueable;

    protected $apprenant;

    public function __construct($apprenant)
    {
        $this->apprenant = $apprenant;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bienvenue à la formation')
            ->line('Bienvenue ' . $this->apprenant->nom . ' ' . $this->apprenant->prenom . '!')
            ->line('Voici vos informations de connexion:')
            ->line('Email: ' . $this->apprenant->email)
            ->line('Mot de passe temporaire: ' . $this->apprenant->password)
            ->action('Activer votre compte', url('/activate-account/' . $this->apprenant->id))
            ->line('Merci de vous être inscrit!');
    }

    public function toArray($notifiable)
    {
        return [
            'apprenant_id' => $this->apprenant->id,
            'message' => 'Bienvenue à la formation',
        ];
    }
}