@extends('layouts.app', ['title' => 'Créer une entreprise - RecruHub'])

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>

<div class="max-w-2xl mx-auto px-4 py-10">
    <!-- Retour à l'accueil -->
    <div class="mb-6">
        <a href="{{ route('recruteur.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-blue-600 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Retour au tableau de bord
        </a>
    </div>

    <!-- Carte Principale -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
            <h1 class="text-xl font-bold flex items-center gap-2">
                <i data-lucide="building-2" class="w-6 h-6"></i>
                Enregistrer votre Entreprise
            </h1>
            <p class="text-xs text-blue-100 mt-1">Configurez votre structure pour commencer à publier vos offres d'emploi.</p>
        </div>

        <form action="{{ route('company.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            <!-- Nom de l'entreprise -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nom de la structure *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Ex: Tech Solutions Inc" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Grille : Localisation & Site Web -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-1">Localisation / Adresse *</label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="Ex: Paris, France ou Distanciel" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                    @error('location') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="website" class="block text-sm font-semibold text-gray-700 mb-1">Site Web</label>
                    <!-- Correction ici : Changement de type="url" à type="text" -->
                    <input type="text" id="website" name="website" value="{{ old('website') }}" placeholder="https://exemple.com" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @error('website') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Description de l'entreprise</label>
                <textarea id="description" name="description" rows="4" placeholder="Présentez brièvement l'activité de votre entreprise, vos valeurs..." class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Téléversement Logo -->
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                <label for="logo" class="block text-sm font-bold text-gray-800 mb-2">Logo de l'entreprise</label>
                <input type="file" id="logo" name="logo" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                <p class="text-[11px] text-gray-400 mt-1">Formats : JPG, PNG, WEBP (Max. 2 Mo)</p>
                @error('logo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Bouton Validation -->
            <div class="pt-2 flex justify-end">
                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-2.5 px-6 rounded-xl shadow-md transition cursor-pointer">
                    Créer l'entreprise
                </button>
            </div>
        </form>
    </div>
</div>

<script>lucide.createIcons();</script>
@endsection