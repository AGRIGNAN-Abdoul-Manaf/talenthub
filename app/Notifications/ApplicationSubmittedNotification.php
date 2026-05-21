<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmittedNotification extends Notification
{
    use Queueable;

    protected $application;
    protected $recipientType; // 'candidat' ou 'recruteur'

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $application, string $recipientType)
    {
        $this->application = $application;
        $this->recipientType = $recipientType;
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
        $candidateName = $this->application->user->name;

        if ($this->recipientType === 'candidat') {
            return (new MailMessage)
                ->subject('📤 Candidature transmise : ' . $jobTitle)
                ->greeting('Bonjour ' . $candidateName . ',')
                ->line('Votre candidature pour le poste de **' . $jobTitle . '** chez **' . $companyName . '** a bien été enregistrée et transmise.')
                ->line('Nous vous tiendrons au courant de son traitement par l\'équipe de recrutement.')
                ->action('Suivre ma candidature', route('candidat.dashboard'))
                ->line('Merci de faire confiance à TalentHub pour votre recherche.');
        } else {
            return (new MailMessage)
                ->subject('⚡ Nouvelle candidature reçue : ' . $jobTitle)
                ->greeting('Bonjour,')
                ->line('Un nouveau candidat, **' . $candidateName . '**, vient de postuler à votre offre d\'emploi : **' . $jobTitle . '**.')
                ->line('Vous pouvez dès à présent consulter son profil complet, lire vos notes d\'évaluation privées et télécharger son CV.')
                ->action('Consulter le dossier RH', route('recruteur.candidate.profile', $this->application->id))
                ->line('TalentHub Pro - Gestion Simplifiée des Talents.');
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

        if ($this->recipientType === 'candidat') {
            return [
                'type' => 'application_sent',
                'title' => 'Candidature transmise',
                'message' => 'Votre candidature pour l\'offre "' . $jobTitle . '" chez ' . $companyName . ' a été envoyée avec succès.',
                'action_url' => route('candidat.dashboard'),
            ];
        } else {
            return [
                'type' => 'application_received',
                'title' => 'Nouvelle candidature !',
                'message' => $candidateName . ' a postulé à votre offre : "' . $jobTitle . '".',
                'action_url' => route('recruteur.candidate.profile', $this->application->id),
            ];
        }
    }
}
