<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegisteredNotification extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $roleName = $this->user->role === 'recruteur' ? 'Recruteur' : 'Candidat';

        return (new MailMessage)
            ->subject('🎉 Bienvenue sur TalentHub !')
            ->greeting('Bonjour ' . $this->user->name . ',')
            ->line('Nous sommes ravis de vous compter parmi nous sur TalentHub, la plateforme de recrutement nouvelle génération.')
            ->line('Votre compte a été créé avec succès en tant que **' . $roleName . '**.')
            ->action('Compléter mon profil', url('/profil'))
            ->line('Merci d\'utiliser notre application !');
    }

    /**
     * Get the array representation of the notification (saved in the database notifications table).
     */
    public function toArray($notifiable): array
    {
        $roleName = $this->user->role === 'recruteur' ? 'Recruteur' : 'Candidat';

        return [
            'type' => 'registration',
            'title' => 'Bienvenue sur TalentHub !',
            'message' => 'Votre inscription en tant que ' . $roleName . ' a été enregistrée avec succès. N\'oubliez pas de compléter votre profil.',
            'action_url' => route('profile.edit'),
        ];
    }
}
