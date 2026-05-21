@extends('layouts.app', ['title' => 'Modifier l\'offre - RecruHub'])

@section('content')
<div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
        
        <div class="flex justify-between items-center mb-8 pb-6 border-b border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 font-sora">Modifier l'offre d'emploi</h1>
                <p class="text-sm text-slate-500">Mettez à jour les critères de votre annonce.</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800">Annuler</a>
        </div>

        <form action="{{ route('jobs.update', $job) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Titre de l'offre *</label>
                <input type="text" name="title" value="{{ old('title', $job->title) }}" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Localisation / Ville *</label>
                    <input type="text" name="location" value="{{ old('location', $job->location) }}" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Type de contrat *</label>
                    <select name="contract_type" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50">
                        @foreach(['CDI', 'CDD', 'Freelance', 'Stage', 'Alternance'] as $type)
                            <option value="{{ $type }}" {{ $job->contract_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Niveau d'études requis *</label>
                    <select name="education_level" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50">
                        @foreach(['Aucun diplôme', 'Bac', 'Bac +2 / +3', 'Bac +5'] as $level)
                            <option value="{{ $level }}" {{ $job->education_level == $level ? 'selected' : '' }}>{{ $level }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Expérience requise *</label>
                    <select name="experience_required" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50">
                        @foreach(['Débutant accepté', '1 à 3 ans', '3 à 5 ans', 'Plus de 5 ans'] as $exp)
                            <option value="{{ $exp }}" {{ $job->experience_required == $exp ? 'selected' : '' }}>{{ $exp }} d'expérience</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Fourchette salariale (Optionnel)</label>
                <input type="text" name="salary_range" value="{{ old('salary_range', $job->salary_range) }}" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Description du poste *</label>
                <textarea name="description" rows="6" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50">{{ old('description', $job->description) }}</textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold font-sora py-3.5 px-4 rounded-xl shadow-md transition duration-150 ease-in-out cursor-pointer text-center">
                    Mettre à jour l'annonce
                </button>
            </div>
        </form>
    </div>
</div>
@endsection