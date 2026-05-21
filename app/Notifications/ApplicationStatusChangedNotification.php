<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChangedNotification extends Notification
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
        $status = $this->application->status;

        if ($status === 'Acceptée') {
            return (new MailMessage)
                ->subject('🎉 Bonne nouvelle ! Votre candidature a été acceptée')
                ->greeting('Félicitations ' . $candidateName . ' !')
                ->line('Nous avons le plaisir de vous annoncer que votre candidature pour le poste de **' . $jobTitle . '** chez **' . $companyName . '** a été **acceptée** !')
                ->line('L\'équipe de recrutement va prendre contact avec vous très prochainement pour définir les prochaines étapes.')
                ->action('Consulter mon espace', route('candidat.dashboard'))
                ->line('Toute l\'équipe de TalentHub se joint à nous pour vous féliciter !');
        } else {
            return (new MailMessage)
                ->subject('Mise à jour concernant votre candidature : ' . $jobTitle)
                ->greeting('Bonjour ' . $candidateName . ',')
                ->line('Nous vous remercions pour l\'intérêt que vous avez porté à l\'offre de **' . $jobTitle . '** au sein de **' . $companyName . '**.')
                ->line('Après étude approfondie de votre candidature, nous sommes au regret de vous informer que nous n\'avons pas retenu votre profil pour cette opportunité.')
                ->line('Les critères de sélection étaient particulièrement sélectifs cette fois-ci, mais nous vous encourageons vivement à continuer à postuler à d\'autres offres sur notre plateforme.')
                ->action('Explorer d\'autres offres', route('jobs.public_index'))
                ->line('Nous vous souhaitons beaucoup de succès dans vos démarches professionnelles futures.');
        }
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $jobTitle = $this->application->job->title;
        $companyName = $this->application->job->company->name ?? 'Recruteur';
        $status = $this->application->status;

        if ($status === 'Acceptée') {
            return [
                'type' => 'application_accepted',
                'title' => 'Candidature acceptée !',
                'message' => 'Félicitations ! Votre candidature pour "' . $jobTitle . '" chez ' . $companyName . ' a été acceptée.',
                'action_url' => route('candidat.dashboard'),
            ];
        } else {
            return [
                'type' => 'application_refused',
                'title' => 'Mise à jour candidature',
                'message' => 'Le recruteur a apporté une réponse à votre candidature pour "' . $jobTitle . '" chez ' . $companyName . '.',
                'action_url' => route('candidat.dashboard'),
            ];
        }
    }
}
