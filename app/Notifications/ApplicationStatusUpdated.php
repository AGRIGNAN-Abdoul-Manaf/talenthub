<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification
{
    use Queueable;

    protected $application;

    // On passe la candidature à la notification pour récupérer les infos
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['mail']; // Nous voulons un envoi par Email
    }

    public function toMail($notifiable)
    {
        $jobTitle = $this->application->job->title;
        $companyName = $this->application->job->company->name ?? 'l\'entreprise';
        $status = $this->application->status; // ex: 'retenu', 'refusé'

        $mail = (new MailMessage)
            ->subject("Évolution de votre candidature pour le poste de $jobTitle")
            ->greeting("Bonjour " . $notifiable->name . ",");

        if ($status === 'retenu') {
            $mail->line("Bonne nouvelle ! Votre candidature pour le poste de **$jobTitle** chez **$companyName** a été retenue.")
                 ->line("Le recruteur va prendre contact avec vous très prochainement pour la suite du processus.")
                 ->action('Voir mon espace', route('candidat.dashboard'))
                 ->success(); // Colore le bouton en vert
        } else {
            $mail->line("Nous vous remercions pour l'intérêt que vous avez porté au poste de **$jobTitle** chez **$companyName**.")
                 ->line("Malheureusement, le recruteur a décidé de ne pas retenir votre profil pour cette offre.")
                 ->line("Ne vous découragez pas, d'autres opportunités vous attendent sur TalentHub !")
                 ->action('Voir d\'autres offres', route('jobs.public_index'))
                 ->error(); // Colore le bouton en rouge/gris
        }

        return $mail->line("Merci d'utiliser TalentHub !");
    }
}