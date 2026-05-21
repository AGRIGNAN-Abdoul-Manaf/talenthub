@extends('layouts.app', ['title' => 'Administration - Offres - RecruHub'])

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
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl text-sm font-medium transition">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Statistiques
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl text-sm font-medium transition">
                    <i data-lucide="users" class="w-4 h-4"></i> Utilisateurs
                </a>
                <a href="{{ route('admin.companies') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl text-sm font-medium transition">
                    <i data-lucide="building" class="w-4 h-4"></i> Entreprises
                </a>
                <a href="{{ route('admin.jobs') }}" class="flex items-center gap-3 px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm font-medium text-white transition">
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

    <!-- Main Content Area -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="mb-8">
            <h1 class="text-3xl font-extrabold font-sora">Gestion des Offres d'emploi</h1>
            <p class="text-sm text-slate-400 mt-1">Modérez les publications d'offres d'emploi, suspendez des annonces ou supprimez les fiches non conformes.</p>
        </header>

        <!-- Filtres et Recherche -->
        <div class="bg-slate-800 border border-slate-700/60 rounded-2xl p-6 mb-8 shadow-lg">
            <form action="{{ route('admin.jobs') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                
                <!-- Recherche -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre du poste..." class="w-full text-xs px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 focus:outline-none focus:border-blue-500 text-slate-200">
                </div>

                <!-- Contrat -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Type de contrat</label>
                    <select name="contract_type" class="w-full text-xs px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 focus:outline-none focus:border-blue-500 text-slate-200">
                        <option value="">Tous les types</option>
                        <option value="CDI" {{ request('contract_type') == 'CDI' ? 'selected' : '' }}>CDI</option>
                        <option value="CDD" {{ request('contract_type') == 'CDD' ? 'selected' : '' }}>CDD</option>
                        <option value="Stage" {{ request('contract_type') == 'Stage' ? 'selected' : '' }}>Stage</option>
                        <option value="Freelance" {{ request('contract_type') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                    </select>
                </div>

                <!-- Statut (Actif/Désactivé) -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Statut</label>
                    <select name="status" class="w-full text-xs px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 focus:outline-none focus:border-blue-500 text-slate-200">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active (En ligne)</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Désactivée (Hors-ligne)</option>
                    </select>
                </div>

                <!-- Bouton -->
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs shadow-md transition shrink-0 cursor-pointer flex items-center justify-center gap-1.5 h-10">
                    <i data-lucide="search" class="w-4 h-4"></i> Rechercher les offres
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm font-medium flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Jobs Table -->
        <div class="bg-slate-800 border border-slate-700/60 rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-700 text-slate-400 text-xs font-semibold uppercase bg-slate-900/30">
                            <th class="py-4 px-6 rounded-l-2xl">Offre d'emploi</th>
                            <th class="py-4 px-6">Entreprise</th>
                            <th class="py-4 px-6">Type Contrat</th>
                            <th class="py-4 px-6">Candidatures reçues</th>
                            <th class="py-4 px-6">État</th>
                            <th class="py-4 px-6 text-right rounded-r-2xl">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50 text-slate-300 text-xs">
                        @forelse($jobs as $job)
                            <tr class="hover:bg-slate-700/20 transition">
                                <td class="py-4 px-6">
                                    <div>
                                        <span class="block font-bold text-slate-100 text-sm">{{ $job->title }}</span>
                                        <span class="block text-[10px] text-slate-500 font-medium mt-0.5">📍 {{ $job->location }} • Publiée le {{ $job->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-semibold">
                                    {{ $job->company->name ?? 'Sans Entreprise' }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/10 font-bold uppercase text-[9px]">
                                        {{ $job->contract_type }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-100">
                                    {{ $job->applications->count() }} candidature(s)
                                </td>
                                <td class="py-4 px-6">
                                    @if($job->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/20">
                                            ● En ligne
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/20 text-rose-400 border border-rose-500/20">
                                            ● Hors-ligne
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Toggle Active Form -->
                                        <form action="{{ route('admin.jobs.toggle', $job) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            @if($job->is_active)
                                                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-1.5 px-3 rounded-lg text-[10px] shadow transition cursor-pointer border-0">
                                                    Hors-ligne
                                                </button>
                                            @else
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1.5 px-3 rounded-lg text-[10px] shadow transition cursor-pointer border-0">
                                                    Mettre en ligne
                                                </button>
                                            @endif
                                        </form>

                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.jobs.delete', $job) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette offre d\'emploi ? Toutes les candidatures associées seront perdues.')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-1.5 px-3 rounded-lg text-[10px] shadow transition cursor-pointer border-0">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-500">
                                    <i data-lucide="briefcase" class="w-10 h-10 mx-auto mb-2 text-slate-600"></i>
                                    Aucune offre d'emploi ne correspond à vos critères.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Laravel Native Pagination -->
            @if($jobs->hasPages())
                <div class="bg-slate-900/40 p-4 border-t border-slate-700/60">
                    {{ $jobs->links() }}
                </div>
            @endif
        </div>

    </main>
</div>

<script>lucide.createIcons();</script>
@endsection
