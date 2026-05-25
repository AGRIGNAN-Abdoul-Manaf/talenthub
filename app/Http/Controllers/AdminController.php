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

        return back()->with('success', "L'offre d'emploi a été supprimée définitivement.");
    }

    /**
     * Espace d'analyses et graphiques détaillés pour l'administrateur (Module 12)
     */
    public function stats()
    {
        $stats = [
            'total_candidates' => User::where('role', 'candidat')->count(),
            'total_recruiters' => User::where('role', 'recruteur')->count(),
            'total_companies' => Company::count(),
            'total_jobs' => JobListing::count(),
            'active_jobs' => JobListing::where('is_active', true)->count(),
            'total_applications' => Application::count(),
        ];

        // Taux de recrutement global
        $accepted = Application::where('status', 'Acceptée')->count();
        $stats['recruitment_rate'] = $stats['total_applications'] > 0 
            ? round(($accepted / $stats['total_applications']) * 100, 1) 
            : 0;

        // Répartitions pour les graphiques Chart.js (passées en JSON)
        $status_distribution = [
            'En attente' => Application::where('status', 'En attente')->count(),
            'Acceptée' => $accepted,
            'Refusée' => Application::where('status', 'Refusée')->count(),
            'Entretien programmé' => Application::where('status', 'Entretien programmé')->count(),
        ];

        $contract_distribution = [
            'CDI' => JobListing::where('contract_type', 'CDI')->count(),
            'CDD' => JobListing::where('contract_type', 'CDD')->count(),
            'Stage' => JobListing::where('contract_type', 'Stage')->count(),
            'Alternance' => JobListing::where('contract_type', 'Alternance')->count(),
        ];

        // Distribution par Catégories de postes
        $categories = JobListing::select('category', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->take(8)
            ->get();

        $category_distribution = [];
        foreach ($categories as $cat) {
            $category_distribution[$cat->category ?? 'Non spécifié'] = $cat->count;
        }

        // Leaderboard des entreprises partenaires
        $companies_stats = Company::with(['jobs.applications'])->get()->map(function ($company) {
            $jobs_count = $company->jobs->count();
            $apps_count = $company->jobs->sum(function ($job) {
                return $job->applications->count();
            });
            $accepted_count = $company->jobs->sum(function ($job) {
                return $job->applications->where('status', 'Acceptée')->count();
            });
            $recruitment_rate = $apps_count > 0 ? round(($accepted_count / $apps_count) * 100, 1) : 0;

            return [
                'id' => $company->id,
                'name' => $company->name,
                'logo_url' => $company->logo_url,
                'jobs_count' => $jobs_count,
                'apps_count' => $apps_count,
                'recruitment_rate' => $recruitment_rate,
            ];
        })->sortByDesc('apps_count')->values()->all();

        return view('admin.stats', compact('stats', 'status_distribution', 'contract_distribution', 'category_distribution', 'companies_stats'));
    }
}
