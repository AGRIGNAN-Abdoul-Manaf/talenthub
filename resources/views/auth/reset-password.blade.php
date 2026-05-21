@extends('layouts.app', ['title' => 'Réinitialiser le mot de passe'])

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md mt-10">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Nouveau mot de passe</h2>
    
    <!-- Affichage des erreurs de validation -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Champs cachés obligatoires pour valider le token de sécurité -->
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label class="block text-gray-700 font-medium mb-1">Adresse Email</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" class="w-full border rounded px-3 py-2 bg-gray-50 focus:outline-blue-500" required readonly>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Nouveau mot de passe</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2 focus:outline-blue-500" required>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-semibold transition">
            Mettre à jour le mot de passe
        </button>
    </form>
</div>
@endsection