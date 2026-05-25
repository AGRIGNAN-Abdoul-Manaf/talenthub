@extends('layouts.app') {{-- Ou ton layout de tableau de bord --}}

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">📈 Module Statistiques & Analyse</h1>

    <!-- 1. Cartes des Indicateurs Clés -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Carte Offres -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-500 uppercase">Offres publiées</div>
            <div class="mt-2 text-3xl font-semibold text-blue-600">{{ $totalJobs }}</div>
        </div>

        <!-- Carte Candidatures -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-500 uppercase">Total Candidatures</div>
            <div class="mt-2 text-3xl font-semibold text-green-600">{{ $totalApplications }}</div>
        </div>

        <!-- Carte Taux de Recrutement -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-500 uppercase">Taux de recrutement</div>
            <div class="mt-2 text-3xl font-semibold text-purple-600">{{ $recruitmentRate }} %</div>
            <p class="text-xs text-gray-400 mt-1">Candidatures au statut "Accepté"</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- 2. Section Graphique -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Activités par Entreprise (Top 5)</h2>
            <div class="relative h-64">
                <canvas id="companyChart"></canvas>
            </div>
        </div>

        <!-- 3. Tableau Statistiques par Entreprise -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Détails des Entreprises</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Entreprise</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Offres</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Candidatures</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($companyStats as $stat)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $stat->name }}</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-600">{{ $stat->jobs_count }}</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-600">{{ $stat->apps_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Inclusion de Chart.js pour le graphique dynamique -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('companyChart').getContext('2d');
    
    // Récupération des données transmises par Laravel
    const labels = {!! json_encode($chartLabels) !!};
    const jobsData = {!! json_encode($chartJobsData) !!};
    const appsData = {!! json_encode($chartAppsData) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Offres publiées',
                    data: jobsData,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)', // Bleu Tailwind
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Candidatures reçues',
                    data: appsData,
                    backgroundColor: 'rgba(34, 197, 94, 0.7)', // Vert Tailwind
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>
@endsection