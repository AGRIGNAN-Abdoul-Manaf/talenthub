@extends('layouts.app', ['title' => 'Publier une offre - RecruHub'])

@section('content')
<div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
        
        <div class="flex justify-between items-center mb-8 pb-6 border-b border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 font-sora">Créer une offre d'emploi</h1>
                <p class="text-sm text-slate-500">Remplissez les détails pour attirer les meilleurs candidats.</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800">Annuler</a>
        </div>

        <form action="{{ route('jobs.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Titre du poste -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Titre de l'offre *</label>
                <input type="text" name="title" required placeholder="Ex: Développeur Fullstack React/Laravel" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50">
            </div>

            <!-- Grille d'informations -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Localisation -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Localisation / Ville *</label>
                    <input type="text" name="location" required placeholder="Ex: Paris, Télétravail, Lomé" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50">
                </div>

                <!-- Type de contrat -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Type de contrat *</label>
                    <select name="contract_type" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50">
                        <option value="CDI">CDI</option>
                        <option value="CDD">CDD</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Stage">Stage</option>
                        <option value="Alternance">Alternance</option>
                    </select>
                </div>

                <!-- Niveau d'études -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Niveau d'études requis *</label>
                    <select name="education_level" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50">
                        <option value="Aucun diplôme">Aucun diplôme</option>
                        <option value="Bac">Bac</option>
                        <option value="Bac +2 / +3">Bac +2 / +3 (Licence/BTS)</option>
                        <option value="Bac +5">Bac +5 (Master/Ingénieur)</option>
                    </select>
                </div>

                <!-- Expérience -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Expérience requise *</label>
                    <select name="experience_required" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50">
                        <option value="Débutant accepté">Débutant accepté</option>
                        <option value="1 à 3 ans">1 à 3 ans d'expérience</option>
                        <option value="3 à 5 ans">3 à 5 ans d'expérience</option>
                        <option value="Plus de 5 ans">Plus de 5 ans (Senior)</option>
                    </select>
                </div>
            </div>

            <!-- Salaire -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Fourchette salariale (Optionnel)</label>
                <input type="text" name="salary_range" placeholder="Ex: 45k€ - 55k€ / an ou 400.000 F CFA / mois" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Description du poste *</label>
                <textarea name="description" rows="6" required placeholder="Missions, compétences recherchées, avantages..." class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50"></textarea>
            </div>

            <!-- Bouton de validation -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold font-sora py-3.5 px-4 rounded-xl shadow-md transition duration-150 ease-in-out cursor-pointer text-center">
                    Publier l'annonce
                </button>
            </div>
        </form>
    </div>
</div>
@endsection