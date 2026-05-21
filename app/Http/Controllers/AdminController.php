<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\JobListing;
use App\Models\Application;

class AdminController extends Controller
{
    /**
     * Espace statistique global pour l'administration
     */
    public function dashboard()
    {
        $stats = [
            'total_candidates' => User::where('role', 'candidat')->count(),
            'total_recruiters' => User::where('role', 'recruteur')->count(),
            'total_companies' => Company::count(),
            'total_jobs' => JobListing::count(),
            'active_jobs' => JobListing::where('is_active', true)->count(),
            'total_applications' => Application::count(),
        ];

        // Dernières inscriptions, entreprises et offres
        $latest_users = User::latest()->take(5)->get();
        $latest_companies = Company::latest()->take(5)->get();
        $latest_jobs = JobListing::with('company')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latest_users', 'latest_companies', 'latest_jobs'));
    }

    /**
     * Liste et filtrage des utilisateurs
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filtre par recherche (Nom, Email)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Filtre par statut (Banni / Actif)
        if ($request->filled('status')) {
            $status = $request->input('status') === 'banned';
            $query->where('is_banned', $status);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users', compact('users'));
    }

    /**
     * Activer / Suspendre le compte d'un utilisateur
     */
    public function toggleBan(User $user)
    {
        // Sécurité : interdire de se bannir soi-même
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas suspendre votre propre compte administrateur.');
        }

        $user->is_banned = !$user->is_banned;
        $user->save();

        $statusMessage = $user->is_banned 
            ? 'Le compte de cet utilisateur a été suspendu avec succès.' 
            : 'Le compte de cet utilisateur a été réactivé.';

        return back()->with('success', $statusMessage);
    }

    /**
     * Liste des entreprises
     */
    public function companies(Request $request)
    {
        $query = Company::with(['user'])->withCount('jobs');

        // Recherche par nom
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $companies = $query->latest()->paginate(10)->withQueryString();

        return view('admin.companies', compact('companies'));
    }

    /**
     * Supprimer une entreprise et toutes ses offres associées
     */
    public function deleteCompany(Company $company)
    {
        // Supprimer toutes les offres de l'entreprise (les cascades BDD feront le reste pour les applications)
        $company->jobs()->delete();
        $company->delete();

        return back()->with('success', 'L\'entreprise et toutes ses offres d\'emploi ont été supprimées.');
    }

    /**
     * Liste et modération des offres d'emploi
     */
    public function jobs(Request $request)
    {
        $query = JobListing::with(['company', 'applications']);

        // Recherche par mot-clé
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        // Filtrer par contrat
        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->input('contract_type'));
        }

        // Filtrer par statut active
        if ($request->filled('status')) {
            $active = $request->input('status') === 'active';
            $query->where('is_active', $active);
        }

        $jobs = $query->latest()->paginate(10)->withQueryString();

        return view('admin.jobs', compact('jobs'));
    }

    /**
     * Activer/Désactiver une offre d'emploi
     */
    public function toggleJobStatus(JobListing $job)
    {
        $job->is_active = !$job->is_active;
        $job->save();

        $status = $job->is_active ? 'activée' : 'désactivée';

        return back()->with('success', "L'offre d'emploi a été {$status} avec succès.");
    }

    /**
     * Supprimer définitivement une offre d'emploi
     */
    public function deleteJob(JobListing $job)
    {
        // Supprimer l'offre (les candidatures associées seront supprimées en cascade)
        $job->delete();

        return back()->with('success', 'L\'offre d' . "'emploi a été supprimée définitivement.");
    }
}
