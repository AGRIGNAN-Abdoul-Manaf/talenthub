@extends('layouts.app', ['title' => 'Explorer les offres - TalentHub'])

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Trouvez votre opportunité</h1>
        <p class="text-slate-500 mb-8">Explorez les offres d'emploi correspondant à vos talents.</p>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- FORMULAIRE DE FILTRES (Barre latérale) -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm h-fit">
                <!-- CORRECTION : Route jobs.public_index utilisée ici -->
                <form action="{{ route('jobs.public_index') }}" method="GET" class="space-y-6">
                    
                    <!-- Mot-clé -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Recherche</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ex: Développeur PHP..." class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500">
                    </div>

                    <!-- Catégorie -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catégorie</label>
                        <select name="category" class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 bg-white">
                            <option value="">Toutes les catégories</option>
                            <option value="Informatique" {{ request('category') == 'Informatique' ? 'selected' : '' }}>Informatique</option>
                            <option value="Design" {{ request('category') == 'Design' ? 'selected' : '' }}>Design</option>
                            <option value="Marketing" {{ request('category') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="Vente" {{ request('category') == 'Vente' ? 'selected' : '' }}>Vente</option>
                        </select>
                    </div>

                    <!-- Contrat -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Type de contrat</label>
                        <select name="contract_type" class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 bg-white">
                            <option value="">Tous les contrats</option>
                            <option value="CDI" {{ request('contract_type') == 'CDI' ? 'selected' : '' }}>CDI</option>
                            <option value="CDD" {{ request('contract_type') == 'CDD' ? 'selected' : '' }}>CDD</option>
                            <option value="Stage" {{ request('contract_type') == 'Stage' ? 'selected' : '' }}>Stage</option>
                            <option value="Freelance" {{ request('contract_type') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                        </select>
                    </div>

                    <!-- Localisation -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Localisation</label>
                        <input type="text" name="location" value="{{ request('location') }}" placeholder="Ex: Paris, Télétravail..." class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500">
                    </div>

                    <!-- Salaire Minimum -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Salaire minimum</label>
                        <input type="number" name="min_salary" value="{{ request('min_salary') }}" placeholder="Ex: 35000" class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500">
                    </div>

                    <!-- Tri -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Trier par</label>
                        <select name="sort" class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 bg-white">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Plus récentes</option>
                            <option value="salary_desc" {{ request('sort') == 'salary_desc' ? 'selected' : '' }}>Mieux rémunérées</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl text-sm shadow transition">
                        Filtrer les résultats
                    </button>
                    
                    @if(request()->anyFilled(['search', 'contract_type', 'location', 'min_salary', 'sort']))
                        <!-- CORRECTION : Route jobs.public_index utilisée aussi pour la réinitialisation -->
                        <a href="{{ route('jobs.public_index') }}" class="block text-center text-xs text-rose-600 hover:underline mt-2">
                            Réinitialiser les filtres
                        </a>
                    @endif
                </form>
            </div>

            <!-- LISTE DES RÉSULTATS -->
            <div class="lg:col-span-3 space-y-4">
                @if($jobs->isEmpty())
                    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
                        <p class="text-slate-500 text-lg">Aucune offre ne correspond à vos critères de recherche.</p>
                    </div>
                @else
                    @foreach($jobs as $job)
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $job->contract_type }}
                                    </span>
                                    @if($job->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $job->category }}
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-bold text-slate-900">{{ $job->title }}</h3>
                                
                                <!-- Métadonnées épurées (Sans émojis) -->
                                <div class="flex flex-wrap gap-4 text-xs text-slate-500 mt-2">
                                    <span>Lieu : {{ $job->location }}</span>
                                    @if($job->salary_range)
                                        <span>Salaire : {{ is_numeric($job->salary_range) ? number_format((float)$job->salary_range, 0, ',', ' ') . ' € / an' : $job->salary_range }}</span>
                                    @endif
                                    <span>Publié {{ $job->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                @if(auth()->check() && auth()->user()->hasRole('candidat'))
                                    <form action="{{ route('jobs.toggleFavorite', $job->id) }}" method="POST" class="inline">
                                        @csrf
                                        <!-- Bouton favori purement textuel (Sans icône SVG) -->
                                        <button type="submit" class="text-xs px-3 py-2.5 rounded-xl border border-slate-200 font-bold transition shadow-sm bg-white cursor-pointer" title="Favori">
                                            @if(auth()->user()->favoriteJobs->contains($job->id))
                                                <span class="text-rose-600">Retirer des favoris</span>
                                            @else
                                                <span class="text-slate-500 hover:text-rose-600">Ajouter aux favoris</span>
                                            @endif
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('jobs.show', $job->id) }}" class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition whitespace-nowrap">
                                    Voir l'offre
                                </a>
                            </div>
                        </div>
                    @endforeach

                    <!-- PAGINATION NATIVE LARAVEL -->
                    <div class="mt-8">
                        {{ $jobs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection