@extends('layouts.app', ['title' => 'Administration - Utilisateurs - RecruHub'])

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
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Vue d'ensemble
                </a>
                <a href="{{ route('admin.stats') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl text-sm font-medium transition">
                    <i data-lucide="trending-up" class="w-4 h-4"></i> Graphiques & Stats
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm font-medium text-white transition">
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

    <!-- Main Content Area -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="mb-8">
            <h1 class="text-3xl font-extrabold font-sora">Gestion des Utilisateurs</h1>
            <p class="text-sm text-slate-400 mt-1">Recherchez, filtrez et gérez les droits d'accès ou suspendez les comptes instantanément.</p>
        </header>

        <!-- Filtres et Recherche -->
        <div class="bg-slate-800 border border-slate-700/60 rounded-2xl p-6 mb-8 shadow-lg">
            <form action="{{ route('admin.users') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                
                <!-- Recherche -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email..." class="w-full text-xs px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 focus:outline-none focus:border-blue-500 text-slate-200">
                </div>

                <!-- Rôle -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Rôle</label>
                    <select name="role" class="w-full text-xs px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 focus:outline-none focus:border-blue-500 text-slate-200">
                        <option value="">Tous les rôles</option>
                        <option value="candidat" {{ request('role') == 'candidat' ? 'selected' : '' }}>Candidat</option>
                        <option value="recruteur" {{ request('role') == 'recruteur' ? 'selected' : '' }}>Recruteur</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                </div>

                <!-- Statut (Ban) -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Statut</label>
                    <select name="status" class="w-full text-xs px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 focus:outline-none focus:border-blue-500 text-slate-200">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif / Autorisé</option>
                        <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Suspendu / Banni</option>
                    </select>
                </div>

                <!-- Bouton -->
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs shadow-md transition shrink-0 cursor-pointer flex items-center justify-center gap-1.5 h-10">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4"></i> Filtrer les membres
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm font-medium flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-sm font-medium flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-400"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Users Table -->
        <div class="bg-slate-800 border border-slate-700/60 rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-700 text-slate-400 text-xs font-semibold uppercase bg-slate-900/30">
                            <th class="py-4 px-6 rounded-l-2xl">Utilisateur</th>
                            <th class="py-4 px-6">Adresse Email</th>
                            <th class="py-4 px-6">Rôle</th>
                            <th class="py-4 px-6">Statut d'accès</th>
                            <th class="py-4 px-6 text-right rounded-r-2xl">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50 text-slate-300 text-xs">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-700/20 transition">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-slate-700 text-slate-200 rounded-full flex items-center justify-center font-bold text-xs uppercase border border-slate-600 shrink-0">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-100 text-sm">{{ $user->name }}</span>
                                            <span class="block text-[10px] text-slate-500 font-medium mt-0.5">Membre depuis {{ $user->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-semibold">
                                    {{ $user->email }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $user->role === 'recruteur' ? 'bg-emerald-500/20 text-emerald-400' : ($user->role === 'admin' ? 'bg-rose-500/20 text-rose-400' : 'bg-blue-500/20 text-blue-400') }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($user->is_banned)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/20 text-rose-400 border border-rose-500/20 animate-pulse">
                                            ● Suspendu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/20">
                                            ● Actif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            @if($user->is_banned)
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1.5 px-3 rounded-lg text-[10px] shadow transition cursor-pointer">
                                                    Réactiver compte
                                                </button>
                                            @else
                                                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-1.5 px-3 rounded-lg text-[10px] shadow transition cursor-pointer">
                                                    Suspendre / Bannir
                                                </button>
                                            @endif
                                        </form>
                                    @else
                                        <span class="text-slate-500 italic text-[10px]">C'est vous</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-slate-500">
                                    <i data-lucide="users" class="w-10 h-10 mx-auto mb-2 text-slate-600"></i>
                                    Aucun utilisateur ne correspond à vos critères.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Laravel Native Pagination -->
            @if($users->hasPages())
                <div class="bg-slate-900/40 p-4 border-t border-slate-700/60">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </main>
</div>

<script>lucide.createIcons();</script>
@endsection
