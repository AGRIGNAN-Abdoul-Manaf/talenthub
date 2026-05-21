@extends('layouts.app', ['title' => 'Mon Profil - TalentHub'])

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        
        <!-- En-tête de la page -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6 text-white flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Mon Profil</h2>
                <p class="text-sm text-blue-100 mt-1">Gérez vos informations personnelles et votre sécurité</p>
            </div>
            <span class="px-3 py-1 bg-white/20 backdrop-blur-md text-white font-semibold text-xs uppercase rounded-full tracking-wider border border-white/10">
                {{ auth()->user()->role }}
            </span>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf

            <!-- 📸 SECTION : PHOTO DE PROFIL (AVATAR) -->
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200/60 flex flex-col sm:flex-row items-center gap-6">
                <div class="relative shrink-0 group">
                    <!-- Photo actuelle -->
                    <img class="h-24 w-24 object-cover rounded-full ring-4 ring-white shadow-md transition duration-300 group-hover:scale-105" 
                         src="{{ $user->avatar_url }}" 
                         alt="Avatar de {{ $user->name }}">
                </div>
                
                <div class="space-y-2 text-center sm:text-left w-full">
                    <label for="avatar" class="block text-sm font-bold text-gray-800">Changer de photo de profil</label>
                    <input type="file" 
                           id="avatar"
                           name="avatar" 
                           accept="image/*" 
                           class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2.5 file:px-4
                                  file:rounded-xl file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100 file:cursor-pointer cursor-pointer" />
                    <p class="text-xs text-gray-400">Formats acceptés : JPG, PNG ou WEBP (Max. 2 Mo)</p>
                    @error('avatar')
                        <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 📝 SECTION : INFORMATIONS GÉNÉRALES -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nom complet</label>
                    <input type="text" 
                           id="name"
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           required>
                    @error('name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Adresse Email</label>
                    <input type="email" 
                           id="email"
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           required>
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 📝 SECTION : COMPLEMENTS DU PROFIL (Pour contact & CV) -->
            <div class="border-t border-gray-100 pt-6">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Compléments de Profil</h3>
                    <p class="text-xs text-gray-500">Ajoutez des détails pour enrichir vos candidatures ou vos informations de recruteur.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Numéro de Téléphone</label>
                        <input type="text" 
                               id="phone"
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}" 
                               placeholder="Ex: +33 6 12 34 56 78"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @error('phone')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="skills" class="block text-sm font-semibold text-gray-700 mb-1">Compétences (Séparées par des virgules)</label>
                        <input type="text" 
                               id="skills"
                               name="skills" 
                               value="{{ old('skills', $user->skills) }}" 
                               placeholder="Ex: PHP, Laravel, TailwindCSS, MySQL, Gestion de Projet"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @error('skills')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bio" class="block text-sm font-semibold text-gray-700 mb-1">Biographie / Résumé</label>
                        <textarea id="bio" 
                                  name="bio" 
                                  rows="4" 
                                  placeholder="Décrivez votre parcours, vos aspirations professionnelles..."
                                  class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 🔒 SECTION : SÉCURITÉ / MOT DE PASSE -->
            <div class="border-t border-gray-100 pt-6">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Modifier le mot de passe</h3>
                    <p class="text-xs text-gray-500 font-medium mt-0.5 bg-amber-50 border border-amber-200 text-amber-800 px-3 py-1.5 rounded-lg inline-block">
                        💡 Laisser vide si vous ne souhaitez pas changer votre mot de passe.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Nouveau mot de passe</label>
                        <input type="password" 
                               id="password"
                               name="password" 
                               placeholder="••••••••"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @error('password')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Confirmer le nouveau mot de passe</label>
                        <input type="password" 
                               id="password_confirmation"
                               name="password_confirmation" 
                               placeholder="••••••••"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                </div>
            </div>

            <!-- 🏁 BOUTONS D'ACTION -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100">
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'recruteur' ? route('recruteur.dashboard') : route('candidat.dashboard')) }}" class="text-sm font-semibold text-gray-500 hover:text-gray-700 hover:underline transition order-2 sm:order-1">
                    ← Retour au tableau de bord
                </a>
                
                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-500/10 transition duration-150 transform active:scale-[0.98] cursor-pointer order-1 sm:order-2">
                    Enregistrer les modifications
                </button>
            </div>
        </form>

    </div>
</div>
@endsection