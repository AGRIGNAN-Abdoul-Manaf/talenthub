<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobListing;
use App\Models\Application;

class RecruteurController extends Controller
{
    public function dashboard()
    {
        // 1. Récupérer l'entreprise du recruteur connecté
        $company = auth()->user()->company;

        if (!$company) {
            $jobsCount = 0;
            $applications = collect();
            $pendingCount = 0;
            $interviewsCount = 0;
        } else {
            // 2. Récupérer le nombre d'offres de cette entreprise
            $jobsCount = JobListing::where('company_id', $company->id)->count();

            // 3. Récupérer toutes les candidatures pour les offres de cette entreprise
            $applications = Application::with(['job.company', 'user'])
                ->whereHas('job', function ($query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->latest()
                ->get();

            // 4. Compter les candidatures en attente et entretiens planifiés
            $pendingCount = $applications->where('status', 'En attente')->count();
            $interviewsCount = $applications->where('status', 'Entretien programmé')->count();
        }

        return view('recruteur.dashboard', compact('applications', 'jobsCount', 'pendingCount', 'interviewsCount'));
    }

    // Méthode pour mettre à jour le statut d'une candidature
    public function updateStatus(Request $request, Application $application)
    {
        $company = auth()->user()->company;

        // Sécurité : s'assurer que l'offre appartient bien à l'entreprise de ce recruteur
        if (!$company || $application->job->company_id !== $company->id) {
            abort(403, 'Action non autorisée.');
        }

        $request->validate([
            'status' => 'required|in:En attente,Acceptée,Refusée,Entretien programmé'
        ]);

        $application->update([
            'status' => $request->status
        ]);

        // Notifier le candidat en cas d'acceptation ou de refus
        if (in_array($request->status, ['Acceptée', 'Refusée'])) {
            $application->user->notify(new \App\Notifications\ApplicationStatusChangedNotification($application));
        }

        return redirect()->back()->with('success', 'Le statut de la candidature a été mis à jour.');
    }

    /**
     * Voir la fiche complète du candidat, avec notes, entretiens, messagerie
     */
    public function candidateProfile(Application $application)
    {
        $company = auth()->user()->company;

        // Sécurité : s'assurer que la candidature concerne une offre de l'entreprise de ce recruteur
        if (!$company || $application->job->company_id !== $company->id) {
            abort(403, 'Action non autorisée.');
        }

        // Charger la relation utilisateur
        $application->load(['user', 'job']);

        return view('recruteur.candidate-profile', compact('application'));
    }

    /**
     * Sauvegarder des notes de recrutement internes sur la candidature
     */
    public function saveNotes(Request $request, Application $application)
    {
        $company = auth()->user()->company;

        if (!$company || $application->job->company_id !== $company->id) {
            abort(403, 'Action non autorisée.');
        }

        $request->validate([
            'recruiter_notes' => 'nullable|string|max:10000'
        ]);

        $application->update([
            'recruiter_notes' => $request->recruiter_notes
        ]);

        return redirect()->back()->with('success', 'Notes de recrutement mises à jour avec succès.');
    }

    /**
     * Planifier un entretien avec le candidat
     */
    public function scheduleInterview(Request $request, Application $application)
    {
        $company = auth()->user()->company;

        if (!$company || $application->job->company_id !== $company->id) {
            abort(403, 'Action non autorisée.');
        }

        $request->validate([
            'interview_date' => 'required|date|after_or_equal:today',
            'interview_time' => 'required',
            'interview_location' => 'required|string|max:255',
            'interview_details' => 'nullable|string|max:5000',
        ]);

        $application->update([
            'interview_date' => $request->interview_date,
            'interview_time' => $request->interview_time,
            'interview_location' => $request->interview_location,
            'interview_details' => $request->interview_details,
            'status' => 'Entretien programmé'
        ]);

        // Ajouter un message système automatique dans le fil de discussion
        $messages = $application->messages ?? [];
        $messages[] = [
            'sender' => 'system',
            'text' => "📅 Entretien planifié le " . date('d/m/Y', strtotime($request->interview_date)) . " à " . $request->interview_time . ". Lieu/Lien : " . $request->interview_location,
            'created_at' => now()->format('Y-m-d H:i:s')
        ];
        $application->update(['messages' => $messages]);

        // Notifier le candidat (Email + Alerte In-App)
        $application->user->notify(new \App\Notifications\InterviewScheduledNotification($application));

        return redirect()->back()->with('success', 'Entretien planifié avec succès. Le candidat a été notifié et le statut de la candidature est passé à "Entretien programmé".');
    }

    /**
     * Envoyer un message direct au candidat
     */
    public function sendMessage(Request $request, Application $application)
    {
        $company = auth()->user()->company;

        if (!$company || $application->job->company_id !== $company->id) {
            abort(403, 'Action non autorisée.');
        }

        $request->validate([
            'message' => 'required|string|max:5000'
        ]);

        $messages = $application->messages ?? [];
        $messages[] = [
            'sender' => 'recruteur',
            'text' => $request->message,
            'created_at' => now()->format('Y-m-d H:i:s')
        ];

        $application->update([
            'messages' => $messages
        ]);

        // Notifier le candidat
        $application->user->notify(new \App\Notifications\NewMessageNotification($application, 'candidat', $request->message));

        return redirect()->back()->with('success', 'Votre message a été envoyé avec succès.');
    }
}