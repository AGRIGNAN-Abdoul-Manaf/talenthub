@extends('layouts.app', ['title' => 'Administration - Graphiques & Statistiques - RecruHub'])

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="min-h-screen bg-slate-900 text-slate-100 flex">
    <!-- Sidebar Admin -->
    <aside class="w-64 bg-slate-950 text-white flex flex-col justify-between p-6 shrink-0 hidden lg:flex border-r border-slate-800">
        <div class="space-y-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center font-bold text-white font-sora">A</div>
                <span class="text-lg font-bold font-sora tracking-wide">RecruHub <span class="text-xs text-rose-500 block font-normal">Admin</span></span>
            </div>

            <!-- Profil Admin -->
            <div class="bg-slate-900 p-3 rounded-xl flex items-center gap-3 border border-slate-800">
                <div class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center font-bold text-sm text-slate-300">
                    {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                </div>
                <div>
                    <h4 class="text-xs font-bold truncate">{{ auth()->user()->name ?? 'Administrateur' }}</h4>
                    <span class="text-[10px] text-rose-400 font-semibold uppercase tracking-wider">Admin</span>
                </div>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl text-sm font-medium transition">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Vue d'ensemble
                </a>
                <a href="{{ route('admin.stats') }}" class="flex items-center gap-3 px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm font-medium text-white transition">
                    <i data-lucide="trending-up" class="w-4 h-4"></i> Graphiques & Stats
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl text-sm font-medium transition">
                    <i data-lucide="users" class="w-4 h-4"></i> Utilisateurs
                </a>
                <a href="{{ route('admin.companies') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl text-sm font-medium transition">
                    <i data-lucide="building" class="w-4 h-4"></i> Entreprises
                </a>
                <a href="{{ route('admin.jobs') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl text-sm font-medium transition">
                    <i data-lucide="briefcase" class="w-4 h-4"></i> Offres d'emploi
                </a>
                <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl text-sm font-medium transition">
                    <i data-lucide="bell" class="w-4 h-4"></i> Notifications
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
            </nav>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-rose-400 hover:bg-rose-950/30 rounded-xl text-sm font-medium transition w-full text-left cursor-pointer border-0 bg-transparent">
                <i data-lucide="log-out" class="w-4 h-4"></i> Se déconnecter
            </button>
        </form>
    </aside>

    <!-- Main Admin View -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold font-sora">Analyses & Graphiques</h1>
                <p class="text-sm text-slate-400 mt-1">Supervision graphique des flux d'offres, candidatures et performances des entreprises.</p>
            </div>
            
            <a href="{{ route('admin.report.pdf') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl text-sm shadow-md hover:shadow-lg transition flex items-center gap-2 cursor-pointer border-0">
                <i data-lucide="download-cloud" class="w-4 h-4"></i>
                Télécharger Rapport PDF
            </a>
        </header>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Jobs Card -->
            <div class="bg-slate-800 border border-slate-700/60 rounded-2xl p-6 flex justify-between items-center shadow-lg">
                <div>
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Offres Publiées</span>
                    <span class="text-3xl font-extrabold font-sora block text-amber-400">{{ $stats['total_jobs'] }}</span>
                    <span class="text-xs text-slate-400 block mt-1">Dont <strong>{{ $stats['active_jobs'] }}</strong> en ligne</span>
                </div>
                <div class="p-4 bg-amber-500/10 text-amber-400 rounded-2xl">
                    <i data-lucide="briefcase" class="w-8 h-8"></i>
                </div>
            </div>

            <!-- Applications Card -->
            <div class="bg-slate-800 border border-slate-700/60 rounded-2xl p-6 flex justify-between items-center shadow-lg">
                <div>
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Candidatures</span>
                    <span class="text-3xl font-extrabold font-sora block text-pink-400">{{ $stats['total_applications'] }}</span>
                    <span class="text-xs text-slate-400 block mt-1">Reçues depuis le lancement</span>
                </div>
                <div class="p-4 bg-pink-500/10 text-pink-400 rounded-2xl">
                    <i data-lucide="send" class="w-8 h-8"></i>
                </div>
            </div>

            <!-- Recruitment Rate Card -->
            <div class="bg-slate-800 border border-slate-700/60 rounded-2xl p-6 flex justify-between items-center shadow-lg">
                <div>
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Taux de Recrutement</span>
                    <span class="text-3xl font-extrabold font-sora block text-emerald-400">{{ $stats['recruitment_rate'] }}%</span>
                    <span class="text-xs text-slate-400 block mt-1">Candidatures acceptées</span>
                </div>
                <div class="p-4 bg-emerald-500/10 text-emerald-400 rounded-2xl">
                    <i data-lucide="check-circle" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
            <!-- Doughnut Chart (Application Status) -->
            <div class="bg-slate-800 border border-slate-700/60 rounded-2xl p-6 shadow-lg xl:col-span-1">
                <h3 class="font-bold font-sora text-sm text-slate-200 mb-4 flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-blue-400"></i> Statuts Candidatures
                </h3>
                <div class="h-60 flex items-center justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Bar Chart (Contract Types) -->
            <div class="bg-slate-800 border border-slate-700/60 rounded-2xl p-6 shadow-lg xl:col-span-1">
                <h3 class="font-bold font-sora text-sm text-slate-200 mb-4 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4 text-purple-400"></i> Types de Contrats
                </h3>
                <div class="h-60 flex items-center justify-center">
                    <canvas id="contractChart"></canvas>
                </div>
            </div>

            <!-- Horizontal Bar Chart (Job Categories) -->
            <div class="bg-slate-800 border border-slate-700/60 rounded-2xl p-6 shadow-lg xl:col-span-1">
                <h3 class="font-bold font-sora text-sm text-slate-200 mb-4 flex items-center gap-2">
                    <i data-lucide="bar-chart-2" class="w-4 h-4 text-amber-400"></i> Top Secteurs
                </h3>
                <div class="h-60 flex items-center justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Leaderboard Table -->
        <div class="bg-slate-800 border border-slate-700/60 rounded-2xl shadow-lg overflow-hidden mb-8">
            <div class="p-6 border-b border-slate-700/60 flex justify-between items-center bg-slate-850/50">
                <h3 class="font-bold font-sora text-base flex items-center gap-2">
                    <i data-lucide="award" class="w-5 h-5 text-indigo-400"></i> Statistiques par Entreprises
                </h3>
                <span class="text-xs text-slate-400 font-medium">Classé par volume de candidatures</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-700 text-slate-400 text-xs font-semibold uppercase bg-slate-900/30">
                            <th class="py-4 px-6 rounded-l-2xl">Entreprise</th>
                            <th class="py-4 px-6 text-center">Offres Publiées</th>
                            <th class="py-4 px-6 text-center">Candidatures reçues</th>
                            <th class="py-4 px-6 text-right rounded-r-2xl">Taux de recrutement</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50 text-slate-300 text-xs">
                        @forelse($companies_stats as $c)
                            <tr class="hover:bg-slate-700/20 transition">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <img class="h-8 w-8 object-cover rounded-lg border border-slate-700 bg-slate-800" 
                                             src="{{ $c['logo_url'] }}" 
                                             alt="Logo {{ $c['name'] }}">
                                        <span class="font-bold text-slate-100 text-sm">{{ $c['name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center font-semibold text-slate-300">
                                    {{ $c['jobs_count'] }} offre(s)
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-slate-100">
                                    {{ $c['apps_count'] }} candidat(s)
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        {{ $c['recruitment_rate'] }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-500">
                                    <i data-lucide="building" class="w-10 h-10 mx-auto mb-2 text-slate-600"></i>
                                    Aucune entreprise enregistrée sur la plateforme.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        lucide.createIcons();

        // 1. Chart - Status Distribution (Doughnut)
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusDistribution = @json($status_distribution);
        
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusDistribution),
                datasets: [{
                    data: Object.values(statusDistribution),
                    backgroundColor: [
                        'rgba(245, 158, 11, 0.75)', // En attente - Orange
                        'rgba(16, 185, 129, 0.75)', // Acceptée - Vert
                        'rgba(239, 68, 68, 0.75)',  // Refusée - Rouge
                        'rgba(99, 102, 241, 0.75)'  // Entretien programmé - Indigo
                    ],
                    borderColor: [
                        '#f59e0b',
                        '#10b981',
                        '#ef4444',
                        '#6366f1'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#cbd5e1',
                            font: { family: 'Sora, sans-serif', size: 10 }
                        }
                    }
                }
            }
        });

        // 2. Chart - Contract Types (Bar)
        const contractCtx = document.getElementById('contractChart').getContext('2d');
        const contractDistribution = @json($contract_distribution);
        
        new Chart(contractCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(contractDistribution),
                datasets: [{
                    label: 'Nombre d\'offres',
                    data: Object.values(contractDistribution),
                    backgroundColor: 'rgba(139, 92, 246, 0.65)',
                    borderColor: '#8b5cf6',
                    borderWidth: 1.5,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    },
                    y: {
                        grid: { color: 'rgba(51, 65, 85, 0.5)' },
                        ticks: { color: '#94a3b8', stepSize: 1, font: { size: 10 } }
                    }
                }
            }
        });

        // 3. Chart - Job Categories (Horizontal Bar)
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryDistribution = @json($category_distribution);
        
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(categoryDistribution),
                datasets: [{
                    label: 'Nombre d\'offres',
                    data: Object.values(categoryDistribution),
                    backgroundColor: 'rgba(236, 72, 153, 0.65)',
                    borderColor: '#ec4899',
                    borderWidth: 1.5,
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(51, 65, 85, 0.5)' },
                        ticks: { color: '#94a3b8', stepSize: 1, font: { size: 10 } }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endsection
