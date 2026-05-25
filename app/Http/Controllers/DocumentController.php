<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Models\Company;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    /**
     * Générer le CV au format PDF pour le candidat connecté
     */
    public function generateCvPdf()
    {
        $user = auth()->user();

        if ($user->role !== 'candidat') {
            abort(403, 'Seuls les candidats peuvent générer leur CV.');
        }

        // Décompte de ses candidatures
        $applicationsCount = $user->applications()->count();

        // Chargement du PDF depuis la vue Blade cv
        $pdf = Pdf::loadView('documents.cv', compact('user', 'applicationsCount'));

        // Définir la taille du papier en A4
        $pdf->setPaper('a4', 'portrait');

        // Retourner le fichier en téléchargement
        return $pdf->download('CV_' . str_replace(' ', '_', $user->name) . '.pdf');
    }

    /**
     * Exporter le dossier RH complet d'une candidature au format PDF
     */
    public function exportApplicationPdf(Application $application)
    {
        $user = auth()->user();
        $company = $user->company;

        // Sécurité : admin ou recruteur de l'entreprise associée à l'offre
        if ($user->role !== 'admin' && (!$company || $application->job->company_id !== $company->id)) {
            abort(403, 'Action non autorisée.');
        }

        // Charger les relations nécessaires
        $application->load(['user', 'job.company']);

        // Chargement de la vue
        $pdf = Pdf::loadView('documents.application', compact('application'));
        
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Candidature_' . str_replace(' ', '_', $application->user->name) . '_' . str_replace(' ', '_', $application->job->title) . '.pdf');
    }

    /**
     * Exporter le rapport statistique global de la plateforme au format PDF
     */
    public function exportAdminReportPdf()
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            abort(403, 'Action réservée aux administrateurs.');
        }

        // Calculs des statistiques identiques à l'espace stats
        $stats = [
            'total_candidates' => User::where('role', 'candidat')->count(),
            'total_recruiters' => User::where('role', 'recruteur')->count(),
            'total_companies' => Company::count(),
            'total_jobs' => JobListing::count(),
            'active_jobs' => JobListing::where('is_active', true)->count(),
            'total_applications' => Application::count(),
        ];

        // Calcul du taux de recrutement global
        $accepted = Application::where('status', 'Acceptée')->count();
        $stats['recruitment_rate'] = $stats['total_applications'] > 0 
            ? round(($accepted / $stats['total_applications']) * 100, 1) 
            : 0;

        // Distribution par statut de candidature
        $stats['applications_by_status'] = [
            'En attente' => Application::where('status', 'En attente')->count(),
            'Acceptée' => $accepted,
            'Refusée' => Application::where('status', 'Refusée')->count(),
            'Entretien programmé' => Application::where('status', 'Entretien programmé')->count(),
        ];

        // Distribution par type de contrat
        $stats['jobs_by_contract'] = [
            'CDI' => JobListing::where('contract_type', 'CDI')->count(),
            'CDD' => JobListing::where('contract_type', 'CDD')->count(),
            'Stage' => JobListing::where('contract_type', 'Stage')->count(),
            'Alternance' => JobListing::where('contract_type', 'Alternance')->count(),
        ];

        // Leaderboard des performances des entreprises
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
                'name' => $company->name,
                'jobs_count' => $jobs_count,
                'apps_count' => $apps_count,
                'recruitment_rate' => $recruitment_rate,
            ];
        })->sortByDesc('apps_count')->values()->all();

        // Récentes activités
        $latest_users = User::latest()->take(5)->get();
        $latest_companies = Company::latest()->take(5)->get();

        // Chargement du template PDF
        $pdf = Pdf::loadView('documents.admin-report', compact('stats', 'companies_stats', 'latest_users', 'latest_companies'));

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Rapport_TalentHub_' . date('d_m_Y') . '.pdf');
    }
}
