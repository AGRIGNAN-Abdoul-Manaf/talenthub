@extends('layouts.app', ['title' => 'Candidatures reçues - RecruHub'])

@section('content')
<div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        
        <!-- Retour en arrière -->
        <div class="mb-6">
            <a href="{{ route('jobs.index') }}" class="text-sm text-slate-500 hover:text-blue-600 transition font-medium">
                ← Retour à la gestion des offres
            </a>
        </div>

        <!-- En-tête -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-8">
            <h1 class="text-xl font-bold text-slate-900 font-sora">Candidatures pour : {{ $job->title }}</h1>
            <p class="text-sm text-slate-500 mt-1">Consultez les motivations des candidats et mettez à jour leur dossier.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            @foreach($job->applications as $application)
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm transition hover:shadow-md">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-4 mb-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $application->user->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">{{ $application->user->name }}</h3>
                                <p class="text-xs text-slate-400">{{ $application->user->email }} • Postulé le {{ $application->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>

                        <div>
                            @if($application->status === 'En attente')
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">● En attente</span>
                            @elseif($application->status === 'Acceptée')
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">✓ Acceptée</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">✕ Refusée</span>
                            @endif
                        </div>
                    </div>

                    <!-- Lettre de motivation -->
                    <div class="mb-6">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Lettre de motivation</h4>
                        <div class="bg-slate-50 p-4 rounded-xl text-sm text-slate-700 leading-relaxed whitespace-pre-line border border-slate-100">
                            {{ $application->cover_letter ?? 'Aucune lettre de motivation fournie.' }}
                        </div>
                    </div>

                    <!-- Actions du recruteur -->
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                        <form action="{{ route('recruteur.applications.status', $application->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Refusée">
                            <button type="submit" class="px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-xl transition" {{ $application->status === 'Refusée' ? 'disabled' : '' }}>
                                Refuser le profil
                            </button>
                        </form>

                        <form action="{{ route('recruteur.applications.status', $application->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Acceptée">
                            <button type="submit" class="px-4 py-2 text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow transition" {{ $application->status === 'Acceptée' ? 'disabled' : '' }}>
                                Accepter le candidat
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection