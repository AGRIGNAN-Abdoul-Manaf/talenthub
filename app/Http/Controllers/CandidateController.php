<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    /**
     * Tableau de bord du candidat
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Récupérer les candidatures du candidat connecté avec ses relations
        $applications = Application::with(['job.company'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Récupérer les offres favorites
        $favoriteJobs = $user->favoriteJobs()->with('company')->latest()->get();

        // Filtrer les candidatures pour trouver les entretiens planifiés
        $interviews = $applications->where('status', 'Entretien programmé')->values();

        return view('candidat.dashboard', compact('applications', 'favoriteJobs', 'interviews'));
    }

    /**
     * Liste ou alias pour le suivi des candidatures
     */
    public function applications()
    {
        return $this->dashboard();
    }

    /**
     * Soumettre une candidature à une offre d'emploi
     */
    public function apply(Request $request, JobListing $job)
    {
        // Sécurité : s'assurer que le candidat n'a pas déjà postulé
        $existing = Application::where('user_id', auth()->id())
            ->where('job_id', $job->id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        // Validation des champs et fichiers
        $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|string|max:5000',
        ], [
            'cv.required' => 'Le CV est obligatoire pour postuler.',
            'cv.file' => 'Le fichier du CV est invalide.',
            'cv.mimes' => 'Le CV doit être au format PDF, DOC ou DOCX.',
            'cv.max' => 'Le CV ne doit pas dépasser 2 Mo.',
            'cover_letter_file.file' => 'Le fichier de la lettre de motivation est invalide.',
            'cover_letter_file.mimes' => 'La lettre de motivation doit être au format PDF, DOC ou DOCX.',
            'cover_letter_file.max' => 'La lettre de motivation ne doit pas dépasser 2 Mo.',
        ]);

        // Stockage physique du CV
        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('uploads/cvs', 'public');
        }

        // Stockage physique de la lettre de motivation si fournie
        $coverLetterPath = null;
        if ($request->hasFile('cover_letter_file')) {
            $coverLetterPath = $request->file('cover_letter_file')->store('uploads/lettres', 'public');
        }

        // Enregistrement de la candidature
        $application = Application::create([
            'user_id' => auth()->id(),
            'job_id' => $job->id,
            'cv_path' => $cvPath,
            'cover_letter_path' => $coverLetterPath,
            'cover_letter' => $request->input('cover_letter'),
            'status' => 'En attente',
        ]);

        // 1. Notifier le candidat (Email + Alerte In-App)
        auth()->user()->notify(new \App\Notifications\ApplicationSubmittedNotification($application, 'candidat'));

        // 2. Notifier le recruteur propriétaire de l'offre
        $recruiter = $job->company->user ?? null;
        if ($recruiter) {
            $recruiter->notify(new \App\Notifications\ApplicationSubmittedNotification($application, 'recruteur'));
        }

        return redirect()->route('candidat.dashboard')->with('success', 'Votre candidature a été transmise avec succès !');
    }

    /**
     * Annuler une candidature
     */
    public function cancelApplication(Application $application)
    {
        // Sécurité : s'assurer que la candidature appartient bien au candidat connecté
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Suppression des fichiers sur le disque pour éviter l'encombrement
        if ($application->cv_path) {
            Storage::disk('public')->delete($application->cv_path);
        }

        if ($application->cover_letter_path) {
            Storage::disk('public')->delete($application->cover_letter_path);
        }

        // Suppression en base de données
        $application->delete();

        return redirect()->route('candidat.dashboard')->with('success', 'Votre candidature a été annulée et vos fichiers ont été supprimés du serveur.');
    }

    /**
     * Ajouter/Retirer une offre des favoris
     */
    public function toggleFavorite(JobListing $job)
    {
        $user = auth()->user();
        
        if ($user->favoriteJobs()->where('job_id', $job->id)->exists()) {
            $user->favoriteJobs()->detach($job->id);
            $message = 'Offre retirée de vos favoris.';
        } else {
            $user->favoriteJobs()->attach($job->id);
            $message = 'Offre ajoutée à vos favoris !';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Répondre à un message du recruteur
     */
    public function replyMessage(Request $request, Application $application)
    {
        // Sécurité : s'assurer que la candidature appartient bien au candidat connecté
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        $request->validate([
            'message' => 'required|string|max:5000'
        ]);

        $messages = $application->messages ?? [];
        
        $messages[] = [
            'sender' => 'candidat',
            'text' => $request->message,
            'created_at' => now()->format('Y-m-d H:i:s')
        ];

        $application->update([
            'messages' => $messages
        ]);

        // Notifier le recruteur de l'offre
        $recruiter = $application->job->company->user ?? null;
        if ($recruiter) {
            $recruiter->notify(new \App\Notifications\NewMessageNotification($application, 'recruteur', $request->message));
        }

        return redirect()->back()->with('success', 'Votre message a été transmis avec succès.');
    }
}