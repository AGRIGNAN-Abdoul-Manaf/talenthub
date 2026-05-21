@extends('layouts.app', ['title' => 'Mot de passe oublié'])

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md mt-10">
    <h2 class="text-2xl font-bold mb-2 text-gray-800 text-center">Mot de passe oublié ?</h2>
    <p class="text-sm text-gray-500 text-center mb-6">Saisissez votre adresse email. Nous vous enverrons un lien de réinitialisation sécurisé.</p>
    
    <!-- 🟢 AJOUT : Affichage du message de succès ou du lien de test fictif -->
    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 text-sm rounded shadow-sm">
            {{ session('status') }}
        </div>
    @endif

    <!-- 🔴 AJOUT : Affichage des erreurs (ex: email inexistant) -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-gray-700 font-medium mb-1">Adresse Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500 @error('email') border-red-500 @enderror" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-semibold transition">
            Envoyer le lien de réinitialisation
        </button>
    </form>

    <p class="text-sm text-gray-600 mt-4 text-center">
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Retour à la page de connexion</a>
    </p>
</div>
@endsection