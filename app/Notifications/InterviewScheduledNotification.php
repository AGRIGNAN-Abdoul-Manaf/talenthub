<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterviewScheduledNotification extends Notification
{
    use Queueable;

    protected $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
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
        
        $dateStr = $this->application->interview_date ? $this->application->interview_date->format('d/m/Y') : '';
        $timeStr = $this->application->interview_time;
        $location = $this->application->interview_location;
        $details = $this->application->interview_details;

        $mail = (new MailMessage)
            ->subject('📅 Invitation à un entretien : ' . $jobTitle)
            ->greeting('Bonjour ' . $candidateName . ',')
            ->line('Nous avons le plaisir de vous informer que votre candidature pour le poste de **' . $jobTitle . '** au sein de **' . $companyName . '** franchit une nouvelle étape.')
            ->line('L\'entreprise souhaite vous rencontrer pour un entretien selon les modalités suivantes :')
            ->line('📌 **Date :** Le ' . $dateStr)
            ->line('⏰ **Heure :** À ' . $timeStr)
            ->line('📍 **Lieu / Lien visioconférence :** ' . $location);

        if ($details) {
            $mail->line('ℹ️ **Consignes complémentaires du recruteur :** ' . $details);
        }

        return $mail
            ->action('Voir les détails sur mon Espace', route('candidat.dashboard'))
            ->line('Nous vous conseillons de bien préparer cet échange et vous souhaitons une excellente réussite !');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $jobTitle = $this->application->job->title;
        $companyName = $this->application->job->company->name ?? 'Recruteur';
        $dateStr = $this->application->interview_date ? $this->application->interview_date->format('d/m') : '';

        return [
            'type' => 'interview',
            'title' => 'Entretien programmé !',
            'message' => 'Un entretien a été programmé le ' . $dateStr . ' pour le poste "' . $jobTitle . '" chez ' . $companyName . '.',
            'action_url' => route('candidat.dashboard'),
        ];
    }
}
