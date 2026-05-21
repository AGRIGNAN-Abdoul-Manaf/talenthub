@extends('layouts.app', ['title' => 'Toutes les offres d\'emploi - RecruHub'])

@section('content')
<div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        
        <!-- En-tête -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Les opportunités disponibles</h1>
            <p class="text-lg text-slate-500 mt-2">Trouvez le poste qui correspond à vos talents et postulez en un clic.</p>
        </div>

        <!-- Liste des offres -->
        @if($jobs->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
                <p class="text-slate-500 text-lg">Aucune offre d'emploi n'est disponible pour le moment.</p>
                <a href="{{ route('home') }}" class="text-blue-600 font-semibold hover:underline mt-2 inline-block">Retour à l'accueil</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($jobs as $job)
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="space-y-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $job->contract_type }}
                            </span>
                            <h2 class="text-xl font-bold text-slate-900">{{ $job->title }}</h2>
                            
                            <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-sm text-slate-500">
                                <div class="flex items-center gap-1">
                                    🏢 <span class="font-medium text-slate-700">{{ $job->company->name ?? 'Entreprise' }}</span>
                                </div>
                                <div>📍 {{ $job->location }}</div>
                                <div>🎓 {{ $job->education_level }}</div>
                            </div>
                        </div>

                        <div class="w-full sm:w-auto text-right">
                            <a href="{{ route('jobs.show', $job->id) }}" class="inline-block w-full sm:w-auto text-center bg-gray-900 hover:bg-gray-800 text-white font-semibold py-2.5 px-5 rounded-xl text-sm transition">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection