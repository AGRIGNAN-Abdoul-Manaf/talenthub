@extends('layouts.app', ['title' => 'Mes offres d\'emploi - RecruHub'])

@section('content')
<div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        
        <!-- En-tête -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 font-sora">Gestion des offres d'emploi</h1>
                <p class="text-sm text-slate-500 mt-1">Retrouvez, modifiez ou basculez le statut de vos publications.</p>
            </div>
            <a href="{{ route('jobs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow transition duration-150 text-sm">
                + Publier une nouvelle offre
            </a>
        </div>

        <!-- Messages de notification (Succès / Erreur) -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- Liste des offres -->
        @if($jobs->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
                <p class="text-slate-500 text-lg">Vous n'avez pas encore publié d'offres d'emploi.</p>
                <a href="{{ route('jobs.create') }}" class="text-blue-600 font-semibold hover:underline mt-2 inline-block">Créer votre première annonce dès maintenant</a>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="p-4 sm:p-5">Poste</th>
                            <th class="p-4 sm:p-5">Contrat / Ville</th>
                            <th class="p-4 sm:p-5">Niveau / Expérience</th>
                            <th class="p-4 sm:p-5 text-center">Candidatures</th>
                            <th class="p-4 sm:p-5 text-center">Statut</th>
                            <th class="p-4 sm:p-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($jobs as $job)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="p-4 sm:p-5">
                                    <div class="font-bold text-slate-900">{{ $job->title }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">Publié le {{ $job->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="p-4 sm:p-5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $job->contract_type }}
                                    </span>
                                    <div class="text-xs text-slate-500 mt-1">📍 {{ $job->location }}</div>
                                </td>
                                <td class="p-4 sm:p-5 text-slate-600">
                                    <div>{{ $job->education_level }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $job->experience_required }}</div>
                                </td>
                                
                                <!-- Colonne Candidatures Reçues -->
                                <td class="p-4 sm:p-5 text-center">
                                    @if($job->applications_count > 0)
                                        <a href="{{ route('recruteur.applications', $job->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                            {{ $job->applications_count }} reçue(s) - Voir
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Aucune</span>
                                    @endif
                                </td>

                                <td class="p-4 sm:p-5 text-center">
                                    <form action="{{ route('jobs.toggle', ['job' => $job->id]) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold cursor-pointer transition {{ $job->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-rose-100 text-rose-800 hover:bg-rose-200' }}">
                                            {{ $job->is_active ? '● Active' : '○ Désactivée' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="p-4 sm:p-5 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('jobs.edit', ['job' => $job->id]) }}" class="text-slate-600 hover:text-blue-600 font-medium transition">Modifier</a>
                                        
                                        <form action="{{ route('jobs.destroy', ['job' => $job->id]) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre définitivement ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-medium cursor-pointer transition">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection