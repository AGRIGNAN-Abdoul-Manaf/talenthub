<?php

namespace App\Http\Controllers;

use App\Models\JobListing; 
use App\Models\Company;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class JobController extends Controller
{
    /**
     * ESPACE RECRUTEUR : Liste des offres de l'entreprise connectée avec compteur de candidatures
     */
    public function index()
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            return redirect()->route('company.create')->with('error', 'Vous devez créer une entreprise avant de gérer vos offres.');
        }

        // Ajoutez l'attribut 'applications_count' de manière optimisée via la relation configurée
        $jobs = $company->jobs()->withCount('applications')->latest()->get();
        
        return view('recruteur.jobs.index', compact('jobs'));
    }

    /**
     * Formulaire de création d'une offre
     */
    public function create()
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            return redirect()->route('company.create')->with('error', 'Veuillez configurer votre entreprise d\'abord.');
        }

        return view('recruteur.jobs.create');
    }

    /**
     * Enregistrer une nouvelle offre en base de données
     */
    public function store(Request $request)
    {
        $company = auth()->user()->company;

        if (!$company) {
            return redirect()->route('company.create')->with('error', 'Action impossible sans entreprise.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'contract_type' => 'required|string',
            'education_level' => 'required|string',
            'experience_required' => 'required|string',
            'salary_range' => 'nullable|string|max:255',
        ]);

        $company->jobs()->create($validated);

        return redirect()->route('jobs.index')->with('success', 'Votre offre d\'emploi a été publiée avec succès !');
    }

    /**
     * Formulaire de modification d'une offre
     */
    public function edit(JobListing $job)
    {
        // Sécurité : On vérifie que l'offre appartient bien à l'entreprise du recruteur connecté
        if ($job->company_id !== auth()->user()->company->id) {
            abort(403, 'Action non autorisée.');
        }

        return view('recruteur.jobs.edit', compact('job'));
    }

    /**
     * Mettre à jour l'offre d'emploi
     */
    public function update(Request $request, JobListing $job)
    {
        if ($job->company_id !== auth()->user()->company->id) {
            abort(403, 'Action non autorisée.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'contract_type' => 'required|string',
            'education_level' => 'required|string',
            'experience_required' => 'required|string',
            'salary_range' => 'nullable|string|max:255',
        ]);

        $job->update($validated);

        return redirect()->route('jobs.index')->with('success', 'L\'offre d\'emploi a été mise à jour avec succès.');
    }

    /**
     * Activer / Désactiver une offre d'emploi (Toggle)
     */
    public function toggle(JobListing $job)
    {
        if ($job->company_id !== auth()->user()->company->id) {
            abort(403, 'Action non autorisée.');
        }

        $job->update([
            'is_active' => !$job->is_active
        ]);

        $status = $job->is_active ? 'activée' : 'désactivée';
        return redirect()->route('jobs.index')->with('success', "L'offre a été {$status} avec succès !");
    }

    /**
     * Supprimer définitivement une offre d'emploi
     */
    public function destroy(JobListing $job)
    {
        if ($job->company_id !== auth()->user()->company->id) {
            abort(403, 'Action non autorisée.');
        }

        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'L\'offre d\'emploi a été supprimée définitivement.');
    }

    /**
     * Afficher la liste des candidats ayant postulé à une offre spécifique
     */
    public function showApplications($id)
    {
        // Utilisation de JobListing à la place de l'ancien modèle Job
        $job = JobListing::with('applications.user')->findOrFail($id);

        // Sécurité : Vérification du propriétaire de l'offre
        if ($job->company_id !== auth()->user()->company->id) {
            abort(403, 'Action non autorisée.');
        }

        return view('recruteur.jobs.applications', compact('job'));
    }

    /**
     * Mettre à jour le statut de la candidature (Acceptée / Refusée)
     */
    public function updateApplicationStatus(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $request->validate([
            'status' => 'required|string|in:En attente,Acceptée,Refusée',
        ]);

        $application->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Le statut de la candidature a été mis à jour avec succès !');
    }

    /**
     * 🌍 ACCÈS PUBLIC : Liste toutes les offres d'emploi actives pour les visiteurs avec recherche et filtres
     */
    public function publicIndex(Request $request)
    {
        $query = JobListing::where('is_active', true)->with('company');

        // Recherche par mot-clé (titre, description ou entreprise)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('company', function($c) use ($search) {
                      $c->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filtre par type de contrat
        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        // Filtre par localisation
        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        // Filtre par salaire minimum
        if ($request->filled('min_salary')) {
            $query->where('salary_range', '>=', (int)$request->min_salary);
        }

        // Tri
        if ($request->input('sort') === 'salary_desc') {
            $query->orderByRaw('CAST(salary_range AS UNSIGNED) DESC');
        } else {
            $query->latest();
        }

        // Pagination avec conservation des filtres dans l'URL
        $jobs = $query->paginate(6)->withQueryString();

        return view('jobs.explore', compact('jobs'));
    }

    /**
     * 🌍 ACCÈS PUBLIC : Afficher les détails complets d'une seule offre d'emploi
     */
    public function show(JobListing $job)
    {
        // Sécurité : Si l'offre est désactivée, on renvoie une erreur 404 au public
        if (!$job->is_active) {
            abort(404, 'Cette offre n\'est plus disponible.');
        }

        return view('jobs.show', compact('job'));
    }

    /**
     * 🆕 ACCÈS PUBLIC : Soumettre une candidature rapide sans compte (Invité)
     */
    public function applyAsGuest(Request $request, JobListing $job)
    {
        // 1. Validation stricte des données et du CV
        $request->validate([
            'guest_name'  => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'cv'          => 'required|file|mimes:pdf|max:2048', // Uniquement PDF, max 2Mo
        ]);

        // 2. Upload sécurisé du CV dans le stockage public (XAMPP storage/app/public/cvs/guests)
        $cvPath = $request->file('cv')->store('cvs/guests', 'public');

        // 3. Création de la candidature (Marquée en attente de vérification d'email)
        $application = Application::create([
            'job_id'      => $job->id,
            'user_id'     => null, 
            'guest_name'  => $request->guest_name,
            'guest_email' => $request->guest_email,
            'cv_path'     => $cvPath,
            'status'      => 'En attente', 
        ]);

        // 4. Génération d'une URL de vérification sécurisée et signée temporairement (Valable 30 min)
        $verificationUrl = URL::temporarySignedRoute(
            'guest.application.verify',
            now()->addMinutes(30),
            ['id' => $application->id, 'token' => sha1($application->guest_email)]
        );

        // 5. Envoi de l'email de validation via le Mailable Laravel
        Mail::to($application->guest_email)->send(new \App\Mail\GuestVerificationMail($verificationUrl, $application));

        return back()->with('success', 'Votre candidature a été pré-enregistrée ! Veuillez vérifier votre boîte mail pour la valider.');
    }

    /**
     * 🆕 ACCÈS PUBLIC : Validation du lien temporaire reçu par e-mail
     */
    public function verifyGuestEmail($id, $token)
    {
        $application = Application::findOrFail($id);

        // Vérification du token de sécurité de l'e-mail
        if (sha1($application->guest_email) !== $token) {
            abort(403, 'Lien de validation invalide.');
        }

        // Validation officielle de la candidature
        $application->update([
            'email_verified_at' => now(),
        ]);

        return redirect()->route('jobs.public_index')->with('success', 'Votre candidature a été validée avec succès et transmise au recruteur !');
    }
}