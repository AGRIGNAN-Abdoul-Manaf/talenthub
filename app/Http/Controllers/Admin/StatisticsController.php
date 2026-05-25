<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use App\Models\Application;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        // 1. Chiffres globaux
        $totalJobs = JobListing::count();
        $totalApplications = Application::count();

        // 2. Calcul du taux de recrutement
        // (On récupère les candidatures ayant le statut 'Accepté' ou 'Recruté' selon ta structure)
        $acceptedApplications = Application::where('status', 'Accepté')->count();
        $recruitmentRate = $totalApplications > 0 
            ? round(($acceptedApplications / $totalApplications) * 100, 2) 
            : 0;

        // 3. Statistiques par entreprise (Top des entreprises avec le nombre d'offres et candidatures)
        $companyStats = Company::select('companies.name')
            ->selectRaw('count(distinct job_listings.id) as jobs_count')
            ->selectRaw('count(distinct applications.id) as apps_count')
            ->leftJoin('job_listings', 'companies.id', '=', 'job_listings.company_id')
            ->leftJoin('applications', 'job_listings.id', '=', 'applications.job_id')
            ->groupBy('companies.id', 'companies.name')
            ->orderBy('jobs_count', 'desc')
            ->take(5) // Limiter au top 5 pour le graphique
            ->get();

        // 4. Préparer les données pour les graphiques
        $chartLabels = $companyStats->pluck('name')->toArray();
        $chartJobsData = $companyStats->pluck('jobs_count')->toArray();
        $chartAppsData = $companyStats->pluck('apps_count')->toArray();

        return view('admin.statistics.index', compact(
            'totalJobs', 
            'totalApplications', 
            'recruitmentRate', 
            'companyStats',
            'chartLabels',
            'chartJobsData',
            'chartAppsData'
        ));
    }
}