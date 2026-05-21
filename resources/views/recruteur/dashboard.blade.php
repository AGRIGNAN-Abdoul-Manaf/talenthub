@extends('layouts.app', ['title' => 'Espace Recruteur - RecruHub'])

@section('content')
<!-- Importation sécurisée des icônes Lucide -->
<script src="https://unpkg.com/lucide@latest"></script>

<div class="min-h-screen bg-slate-50 flex font-sans antialiased text-slate-800">
    
    <!-- 1. SIDEBAR RECRUTEUR (Style SaaS Premium) -->
    <aside class="w-64 bg-slate-950 text-white flex flex-col justify-between p-5 shrink-0 hidden lg:flex border-r border-slate-900 shadow-xl">
        <div class="space-y-7">
            <!-- Logo de la marque -->
            <div class="flex items-center gap-3 px-2">
                <div class="w-9 h-9 bg-gradient-to-tr from-indigo-600 to-indigo-500 rounded-xl flex items-center justify-center font-black text-white shadow-lg shadow-indigo-600/30">
                    R
                </div>
                <div>
                    <span class="text-base font-extrabold tracking-tight block">RecruHub</span>
                    <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider block -mt-1">Espace Pro</span>
                </div>
            </div>

            <!-- Profil de l'entreprise connectée -->
            <div class="bg-slate-900/60 border border-slate-900 p-3.5 rounded-2xl flex items-center gap-3 shadow-inner">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center font-extrabold text-sm text-white shadow-md">
                    {{ substr(auth()->user()->name ?? 'RE', 0, 2) }}
                </div>
                <div class="overflow-hidden">
                    <h4 class="text-xs font-bold text-slate-200 truncate">{{ auth()->user()->name ?? 'Entreprise' }}</h4>
                    <span class="inline-flex items-center text-[9px] text-emerald-400 bg-emerald-950/50 px-2 py-0.5 rounded-full font-bold uppercase tracking-wide mt-1">
                        ● Recruteur
                    </span>
                </div>
            </div>

            <!-- Liens de navigation principaux -->
            <nav class="space-y-1">
                <a href="{{ route('recruteur.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 rounded-xl text-xs font-bold text-white transition-all shadow-md shadow-indigo-600/10">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-white"></i> Candidatures reçues
                </a>
                
                <a href="{{ route('jobs.index') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-900 hover:text-slate-100 rounded-xl text-xs font-semibold transition-all group">
                    <i data-lucide="briefcase" class="w-4 h-4 text-slate-500 group-hover:text-slate-300"></i> Mes Offres
                </a>
                
                <a href="{{ route('jobs.create') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-900 hover:text-slate-100 rounded-xl text-xs font-semibold transition-all group">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-slate-500 group-hover:text-slate-300"></i> Publier une offre
                </a>
                
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-900 hover:text-slate-100 rounded-xl text-xs font-semibold transition-all group">
                    <i data-lucide="user" class="w-4 h-4 text-slate-500 group-hover:text-slate-300"></i> Mon Profil
                </a>
                
                <a href="{{ route('notifications.index') }}" class="flex items-center justify-between px-4 py-3 text-slate-400 hover:bg-slate-900 hover:text-slate-100 rounded-xl text-xs font-semibold transition-all group">
                    <div class="flex items-center gap-3">
                        <i data-lucide="bell" class="w-4 h-4 text-slate-500 group-hover:text-slate-300"></i> Notifications
                    </div>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-sm">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
            </nav>
        </div>

        <!-- Déconnexion sécurisée en bas de Sidebar -->
        <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3 text-rose-400 hover:bg-rose-950/30 rounded-xl text-xs font-bold transition-all w-full text-left cursor-pointer border-0 bg-transparent group">
                <i data-lucide="log-out" class="w-4 h-4 text-rose-400 group-hover:translate-x-0.5 transition-transform"></i> Se déconnecter
            </button>
        </form>
    </aside>

    <!-- 2. CONTENU PRINCIPAL DE LA PAGE -->
    <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
        
        <!-- En-tête : Titre & Compteurs intégrés -->
        <header class="mb-10 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6">
            <div>
                <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">Tableau de bord</h1>
                <p class="text-sm text-slate-400 mt-1">Supervisez vos processus de recrutement et l'état de vos offres d'emploi en temps réel.</p>
            </div>
            
            <!-- Grille des indicateurs KPI -->
            <div class="flex gap-4 flex-wrap w-full xl:w-auto">
                <!-- Case Offres Postées -->
                <div class="bg-white p-4 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4 min-w-[160px] flex-1 xl:flex-none">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                        <i data-lucide="folder-open" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Offres</span>
                        <span class="text-2xl font-black text-slate-900 block leading-tight">{{ $jobsCount }}</span>
                    </div>
                </div>

                <!-- Case À Traiter -->
                <div class="bg-white p-4 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4 min-w-[160px] flex-1 xl:flex-none">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">À traiter</span>
                        <span class="text-2xl font-black text-amber-600 block leading-tight">{{ $pendingCount }}</span>
                    </div>
                </div>

                <!-- Case Entretiens Validés -->
                <div class="bg-white p-4 rounded-2xl border border-slate-200/60 shadow-sm flex items-center gap-4 min-w-[160px] flex-1 xl:flex-none">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Entretiens</span>
                        <span class="text-2xl font-black text-blue-600 block leading-tight">{{ $interviewsCount }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- 3. ZONE : LISTE DES CANDIDATS POSTULANTS -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            
            <!-- Titre du tableau -->
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2.5">
                    <i data-lucide="users" class="w-4 h-4 text-indigo-600"></i> Dossiers de candidatures reçus
                </h3>
            </div>

            <!-- Alerte Flash Laravel Session -->
            @if(session('success'))
                <div class="m-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center gap-2.5 shadow-sm animate-fade-in">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Conteneur adaptatif du tableau -->
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 text-[11px] font-bold uppercase tracking-wider bg-slate-50/20">
                            <th class="py-4 px-6">Candidat</th>
                            <th class="py-4 px-6">Poste recherché</th>
                            <th class="py-4 px-6">Pièces jointes</th>
                            <th class="py-4 px-6">Statut du dossier</th>
                            <th class="py-4 px-6 text-right">Actions de traitement</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($applications as $application)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <!-- Infos Profil Candidat -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center font-black text-slate-700 border border-slate-200/40 shadow-inner group-hover:bg-white group-hover:text-indigo-600 transition-all">
                                            {{ substr($application->user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-900 text-sm leading-none mb-1">{{ $application->user->name }}</span>
                                            <span class="block text-slate-400 font-medium">{{ $application->user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Intitulé du Poste ciblé -->
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-800 text-sm block leading-tight">{{ $application->job->title }}</span>
                                    <span class="block text-[10px] text-slate-400 font-medium mt-1">Reçu {{ $application->created_at->diffForHumans() }}</span>
                                </td>
                                
                                <!-- Liens vers les fichiers stockés -->
                                <td class="py-4 px-6">
                                    <div class="flex flex-wrap gap-1.5">
                                        <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg font-bold transition-colors">
                                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Curriculum (CV)
                                        </a>
                                        @if($application->cover_letter_path)
                                            <a href="{{ asset('storage/' . $application->cover_letter_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg font-bold transition-colors">
                                                <i data-lucide="file" class="w-3.5 h-3.5"></i> Lettre de Motivation
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Badges de statuts dynamiques -->
                                <td class="py-4 px-6">
                                    @php
                                        $statusColors = [
                                            'En attente' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                            'Acceptée' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                            'Refusée' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                            'Entretien programmé' => 'bg-blue-50 text-blue-700 border-blue-200/60',
                                        ];
                                        $colorClass = $statusColors[$application->status] ?? 'bg-slate-50 text-slate-700 border-slate-200/60';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full font-bold border {{ $colorClass }} text-[10px] uppercase tracking-wide shadow-sm">
                                        {{ $application->status }}
                                    </span>
                                </td>
                                
                                <!-- Gestion interactive du statut & Redirection RH -->
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2.5">
                                        <form action="{{ route('recruteur.applications.updateStatus', $application) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <select name="status" onchange="this.form.submit()" class="text-xs bg-slate-50 hover:bg-slate-100 border border-slate-200/80 rounded-xl p-2 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 cursor-pointer shadow-sm transition-all">
                                                <option value="">Modifier l'état...</option>
                                                <option value="En attente" {{ $application->status === 'En attente' ? 'selected' : '' }}>En attente</option>
                                                <option value="Entretien programmé" {{ $application->status === 'Entretien programmé' ? 'selected' : '' }}>📆 Planifier Entretien</option>
                                                <option value="Acceptée" {{ $application->status === 'Acceptée' ? 'selected' : '' }}>✓ Accepter le profil</option>
                                                <option value="Refusée" {{ $application->status === 'Refusée' ? 'selected' : '' }}>✕ Écarter le profil</option>
                                            </select>
                                        </form>

                                        <!-- Accès au profil RH détaillé -->
                                        <a href="{{ route('recruteur.candidate.profile', $application->id) }}" class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-indigo-600 text-white font-bold py-2 px-3 rounded-xl shadow-sm transition-all active:scale-[0.98]">
                                            <i data-lucide="sliders" class="w-3.5 h-3.5"></i> Fiche RH
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <!-- Écran vide propre si aucun résultat -->
                            <tr>
                                <td colspan="5" class="text-center py-20 text-slate-400 bg-white">
                                    <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100 text-slate-300">
                                        <i data-lucide="folder-search" class="w-6 h-6"></i>
                                    </div>
                                    <span class="block text-sm font-extrabold text-slate-800">Aucun dossier à traiter pour l'instant</span>
                                    <span class="text-xs text-slate-400 block mt-1 max-w-xs mx-auto">Dès qu'un talent postulera à l'une de vos offres d'emploi, ses informations complètes apparaîtront sur cette table.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Initialisation automatique des vecteurs Lucide -->
<script>lucide.createIcons();</script>
@endsectiony>
                </table>
            </div>
        </div>
    </main>
</div>

<script>lucide.createIcons();</script>
@endsection