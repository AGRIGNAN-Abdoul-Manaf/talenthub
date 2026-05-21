<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewMessageNotification extends Notification
{
    use Queueable;

    protected $application;
    protected $recipientType; // 'candidat' ou 'recruteur'
    protected $messageText;

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $application, string $recipientType, string $messageText)
    {
        $this->application = $application;
        $this->recipientType = $recipientType;
        $this->messageText = $messageText;
    }

    /**
     * Get the notification's delivery channels.
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
        $jobTitle = $this->application->job->title;
        $companyName = $this->application->job->company->name ?? 'Recruteur';
        $excerpt = Str::limit($this->messageText, 100);

        if ($this->recipientType === 'candidat') {
            return (new MailMessage)
                ->subject('💬 Nouveau message de ' . $companyName)
                ->greeting('Bonjour ' . $this->application->user->name . ',')
                ->line('Vous avez reçu un nouveau message de **' . $companyName . '** concernant votre candidature pour le poste de **' . $jobTitle . '** :')
                ->line('» *"' . $excerpt . '"*')
                ->action('Ouvrir ma discussion', route('candidat.dashboard'))
                ->line('N\'hésitez pas à répondre directement depuis votre espace personnel.');
        } else {
            $candidateName = $this->application->user->name;
            return (new MailMessage)
                ->subject('💬 Nouveau message de ' . $candidateName)
                ->greeting('Bonjour,')
                ->line('Le candidat **' . $candidateName . '** a envoyé un nouveau message concernant l\'offre **' . $jobTitle . '** :')
                ->line('» *"' . $excerpt . '"*')
                ->action('Consulter le dossier RH', route('recruteur.candidate.profile', $this->application->id))
                ->line('Vous pouvez lire l\'historique des échanges et lui répondre depuis sa fiche RH.');
        }
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $jobTitle = $this->application->job->title;
        $companyName = $this->application->job->company->name ?? 'Recruteur';
        $candidateName = $this->application->user->name;
        $excerpt = Str::limit($this->messageText, 80);

        if ($this->recipientType === 'candidat') {
            return [
                'type' => 'new_message',
                'title' => 'Nouveau message reçu !',
                'message' => 'Message de ' . $companyName . ' : "' . $excerpt . '"',
                'action_url' => route('candidat.dashboard'),
            ];
        } else {
            return [
                'type' => 'new_message',
                'title' => 'Nouveau message candidat',
                'message' => 'Message de ' . $candidateName . ' : "' . $excerpt . '"',
                'action_url' => route('recruteur.candidate.profile', $this->application->id),
            ];
        }
    }
}
