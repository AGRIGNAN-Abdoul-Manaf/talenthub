@extends('layouts.app', ['title' => 'Tableau de bord - RecruHub'])

@section('content')
<!-- Petites icônes légères -->
<script src="https://unpkg.com/lucide@latest"></script>

<div class="max-w-4xl mx-auto px-4 py-10">
    
    <!-- 1. EN-TÊTE SIMPLE -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 mb-8 border-b border-gray-200 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="layout-dashboard" class="w-6 h-6 text-blue-600"></i>
                Tableau de bord
            </h1>
            <p class="text-sm text-gray-500 mt-1">Bienvenue sur votre espace général RecruHub.</p>
        </div>
        
        <!-- Infos Profil Flash -->
        <div class="flex items-center gap-3 bg-white p-2 pr-4 rounded-xl border border-gray-200 shadow-sm">
            <img class="h-9 w-9 object-cover rounded-full" src="{{ auth()->user()->avatar_url }}" alt="Avatar">
            <div>
                <p class="text-sm font-bold text-gray-800 leading-tight">{{ auth()->user()->name }}</p>
                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600">{{ auth()->user()->role }}</span>
            </div>
        </div>
    </div>

    <!-- 2. LE TABLEAU DE BORD (MENU SIMPLE) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-700 text-sm tracking-wide uppercase">Navigation principale</h2>
        </div>

        <div class="divide-y divide-gray-100">
            
            <!-- Ligne : Tableau de bord (Page actuelle) -->
            <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <i data-lucide="home" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Accueil Général</p>
                        <p class="text-xs text-gray-500">Vue d'ensemble de votre account</p>
                    </div>
                </div>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">Actif</span>
            </div>

            <!-- Ligne conditionnelle : Utilisateurs (Pour l'Admin) -->
            @if(auth()->user()->hasRole('admin'))
                <a href="#" class="p-4 flex items-center justify-between hover:bg-gray-50 transition group">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-50 text-purple-600 rounded-lg group-hover:bg-purple-600 group-hover:text-white transition">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm group-hover:text-purple-600 transition">Gestion des Utilisateurs</p>
                            <p class="text-xs text-gray-500">Voir la liste des candidats et recruteurs</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 group-hover:translate-x-1 transition transform"></i>
                </a>
            @endif

            <!-- Ligne : Mon Profil -->
            <a href="{{ route('profile.edit') }}" class="p-4 flex items-center justify-between hover:bg-gray-50 transition group">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-50 text-green-600 rounded-lg group-hover:bg-green-600 group-hover:text-white transition">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm group-hover:text-green-600 transition">Mon profil</p>
                        <p class="text-xs text-gray-500">Modifier vos informations et votre photo</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 group-hover:translate-x-1 transition transform"></i>
            </a>

            <!-- Lignes d'accès spécifiques : Espace Candidat -->
            @if(auth()->user()->hasRole('candidat') || auth()->user()->hasRole('admin'))
                <a href="{{ route('candidat.dashboard') }}" class="p-4 flex items-center justify-between hover:bg-gray-50 transition group">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-orange-50 text-orange-600 rounded-lg group-hover:bg-orange-600 group-hover:text-white transition">
                            <i data-lucide="briefcase" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm group-hover:text-orange-600 transition">Espace Candidat</p>
                            <p class="text-xs text-gray-500">Trouver des offres et gérer vos CVs</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 group-hover:translate-x-1 transition transform"></i>
                </a>
            @endif

            <!-- Lignes d'accès spécifiques : Espace Recruteur -->
            @if(auth()->user()->hasRole('recruteur') || auth()->user()->hasRole('admin'))
                <a href="{{ route('recruteur.dashboard') }}" class="p-4 flex items-center justify-between hover:bg-gray-50 transition group">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm group-hover:text-indigo-600 transition">Espace Recruteur</p>
                            <p class="text-xs text-gray-500">Publier des offres et voir vos candidats</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 group-hover:translate-x-1 transition transform"></i>
                </a>
            @endif

            <!-- Ligne d'accès spécifique : Espace Gestion Entreprise (Pour le Recruteur ou l'Admin) -->
            @if(auth()->user()->hasRole('recruteur') || auth()->user()->hasRole('admin'))
                <a href="{{ auth()->user()->company ? route('company.edit') : route('company.create') }}" class="p-4 flex items-center justify-between hover:bg-gray-50 transition group">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-teal-50 text-teal-600 rounded-lg group-hover:bg-teal-600 group-hover:text-white transition">
                            <i data-lucide="building-2" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm group-hover:text-teal-600 transition">Mon Entreprise</p>
                            <p class="text-xs text-gray-500">
                                {{ auth()->user()->company ? 'Gérer et modifier les infos de la structure' : 'Enregistrer votre structure (Obligatoire)' }}
                            </p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 group-hover:translate-x-1 transition transform"></i>
                </a>
            @endif

        </div>
    </div>

    <!-- 3. ZONE DE DÉCONNEXION -->
    <div class="mt-6 flex justify-end">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-red-600 transition cursor-pointer">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                Se déconnecter de la session
            </button>
        </form>
    </div>

</div>

<script>
    // Activation des petites icônes Lucide
    lucide.createIcons();
</script>
@endsection