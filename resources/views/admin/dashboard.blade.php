@extends('layouts.app', ['title' => 'Administration - Dashboard - RecruHub'])

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>

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
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm font-medium text-white transition">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Vue d'ensemble
                </a>
                <a href="{{ route('admin.stats') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl text-sm font-medium transition">
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
        <header class="mb-8">
            <h1 class="text-3xl font-extrabold font-sora">Tableau de bord Général</h1>
            <p class="text-sm text-slate-400 mt-1">Supervision de la plateforme, modération et données analytiques globales.</p>
        </header>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            
            <!-- Candidates Card -->
            <div class="bg-slate-800 border border-slate-700/60 rounded-2xl p-6 flex justify-between items-center shadow-lg">
                <div>
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Candidats</span>
                    <span class="text-3xl font-extrabold font-sora block text-blue-400">{{ $stats['total_candidates'] }}</span>
                </div>
                <div class="p-4 bg-blue-500/10 text-blue-400 rounded-2xl">
                    <i data-lucide="users" class="w-8 h-8"></i>
                </div>
            </div>

            <!-- Recruiters Card -->
            <div class="bg-slate-800 border border-slate-700/60 rounded-2xl p-6 flex justify-between items-center shadow-lg">
                <div>
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Recruteurs</span>
                    <span class="text-3xl font-extrabold font-sora block text-emerald-400">{{ $stats['total_recruiters'] }}</span>
                </div>
                <div class="p-4 bg-emerald-500/10 text-emerald-400 rounded-2xl">
                    <i data-lucide="user-check" class="w-8 h-8"></i>
                </div>
            </div>

            <!-- Companies Card -->
            <div class="bg-slate-800 border border-slate-700/60 rounded-2xl p-6 flex justify-between items-center shadow-lg">
                <div>
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Entreprises</span>
                    <span class="text-3xl font-extrabold font-sora block text-purple-400">{{ $stats['total_companies'] }}</span>
                </div>
                <div class="p-4 bg-purple-500/10 text-purple-400 rounded-2xl">
                    <i data-lucide="building" class="w-8 h-8"></i>
                </div>
            </div>

            <!-- Jobs Card -->
            <div class="bg-slate-800 border border-slate-700/60 rounded-2xl p-6 flex justify-between items-center shadow-lg">
                <div>
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Offres publiées</span>
                    <span class="text-3xl font-extrabold font-sora block text-amber-400">{{ $stats['total_jobs'] }} <span class="text-xs font-normal text-slate-400">({{ $stats['active_jobs'] }} actives)</span></span>
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
                </div>
                <div class="p-4 bg-pink-500/10 text-pink-400 rounded-2xl">
                    <i data-lucide="send" class="w-8 h-8"></i>
                </div>
            </div>
            
        </div>

        <!-- Recent Items Section -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            
            <!-- Latest Users -->
            <div class="bg-slate-800 border border-slate-700/60 rounded-2xl p-6 shadow-lg">
                <div class="flex justify-between items-center mb-4 border-b border-slate-700/50 pb-3">
                    <h3 class="font-bold font-sora text-base flex items-center gap-2">
                        <i data-lucide="users" class="w-5 h-5 text-blue-400"></i> Nouveaux Utilisateurs
                    </h3>
                    <a href="{{ route('admin.users') }}" class="text-xs font-semibold text-blue-400 hover:underline">Gérer tous</a>
                </div>
                <div class="space-y-4">
                    @forelse($latest_users as $user)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-900/40 border border-slate-700/30">
                            <div>
                                <span class="block font-bold text-slate-100 text-sm">{{ $user->name }}</span>
                                <span class="block text-[10px] text-slate-400">{{ $user->email }}</span>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase {{ $user->role === 'recruteur' ? 'bg-emerald-500/20 text-emerald-300' : ($user->role === 'candidat' ? 'bg-blue-500/20 text-blue-300' : 'bg-rose-500/20 text-rose-300') }}">
                                {{ $user->role }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 italic py-4">Aucun utilisateur enregistré.</p>
                    @endforelse
                </div>
            </div>

            <!-- Latest Companies -->
            <div class="bg-slate-800 border border-slate-700/60 rounded-2xl p-6 shadow-lg">
                <div class="flex justify-between items-center mb-4 border-b border-slate-700/50 pb-3">
                    <h3 class="font-bold font-sora text-base flex items-center gap-2">
                        <i data-lucide="building" class="w-5 h-5 text-purple-400"></i> Nouvelles Entreprises
                    </h3>
                    <a href="{{ route('admin.companies') }}" class="text-xs font-semibold text-purple-400 hover:underline">Gérer toutes</a>
                </div>
                <div class="space-y-4">
                    @forelse($latest_companies as $company)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-900/40 border border-slate-700/30">
                            <div>
                                <span class="block font-bold text-slate-100 text-sm">{{ $company->name }}</span>
                                <span class="block text-[10px] text-slate-400">📍 {{ $company->location }}</span>
                            </div>
                            <span class="text-[10px] text-slate-400">Inscrit {{ $company->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 italic py-4">Aucune entreprise enregistrée.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </main>
</div>

<script>lucide.createIcons();</script>
@endsection
