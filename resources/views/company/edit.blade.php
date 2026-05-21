@extends('layouts.app', ['title' => 'Modifier l\'entreprise - RecruHub'])

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>

<div class="max-w-2xl mx-auto px-4 py-10">
    <div class="mb-6">
        <a href="{{ route('recruteur.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-blue-600 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Retour au tableau de bord
        </a>
    </div>

    <!-- Formulaire Principal de Mise à jour -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="p-6 bg-gradient-to-r from-indigo-600 to-slate-900 text-white flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-6 h-6"></i>
                    Modifier l'Entreprise
                </h1>
                <p class="text-xs text-indigo-100 mt-1">Mettez à jour les informations visibles par les candidats.</p>
            </div>
            
            <!-- Aperçu miniature du logo actuel -->
            <img class="h-12 w-12 object-cover rounded-xl border border-white/20 bg-white shadow-sm" src="{{ $company->logo_url }}" alt="Logo">
        </div>

        <form action="{{ route('company.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            <!-- Nom de l'entreprise -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nom de la structure *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $company->name) }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Grille : Localisation & Site Web -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-1">Localisation / Adresse *</label>
                    <input type="text" id="location" name="location" value="{{ old('location', $company->location) }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                    @error('location') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="website" class="block text-sm font-semibold text-gray-700 mb-1">Site Web</label>
                    <!-- Correction ici : Changement de type="url" à type="text" -->
                    <input type="text" id="website" name="website" value="{{ old('website', $company->website) }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @error('website') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Description de l'entreprise</label>
                <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">{{ old('description', $company->description) }}</textarea>
                @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Remplacement Logo -->
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                <label for="logo" class="block text-sm font-bold text-gray-800 mb-1">Remplacer le logo</label>
                <input type="file" id="logo" name="logo" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                @error('logo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Bouton Validation -->
            <div class="pt-2 flex justify-end">
                <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm py-2.5 px-6 rounded-xl shadow-md transition cursor-pointer">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    <!-- 🚨 ZONE DANGER : Suppression de l'entreprise -->
    <div class="bg-red-50 border border-red-200 rounded-xl p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-sm font-bold text-red-800 flex items-center gap-1.5">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                Zone de danger
            </h3>
            <p class="text-xs text-red-600 mt-0.5">La suppression supprimera également toutes vos offres d'emploi rattachées.</p>
        </div>
        
        <form action="{{ route('company.destroy') }}" method="POST" onsubmit="return confirm('Êtes-vous certain de vouloir supprimer définitivement votre entreprise ainsi que toutes ses annonces ? Cette action est irréversible.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-white hover:bg-red-600 text-red-600 hover:text-white border border-red-200 hover:border-red-600 text-xs font-bold py-2 px-4 rounded-xl transition cursor-pointer">
                Supprimer l'entreprise
            </button>
        </form>
    </div>
</div>

<script>lucide.createIcons();</script>
@endsectionz