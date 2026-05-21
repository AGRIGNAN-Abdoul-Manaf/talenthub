<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentHub - Connexion</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <!-- Conteneur style Application Mobile -->
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md border border-gray-100 overflow-hidden flex flex-col">
        
        <!-- Indicateur de barre supérieure style App Mobile -->
        <div class="w-full flex justify-center pt-3">
            <div class="w-12 h-1 bg-gray-200 rounded-full"></div>
        </div>

        <!-- En-tête de l'application -->
        <div class="px-8 pt-6 pb-4 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-2xl shadow-md shadow-blue-600/30 font-black text-xl mb-3">
                TH
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">De retour par ici ?</h1>
            <p class="text-xs text-gray-400 mt-1">Connectez-vous à votre espace TalentHub</p>
        </div>

        <!-- Formulaire de Connexion -->
        <form action="{{ route('login') }}" method="POST" class="px-8 pb-8 space-y-5 flex-grow">
            @csrf

            <!-- CHAMPS DE SAISIE -->
            <div class="space-y-4">
                
                <!-- Email -->
                <div class="relative">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:bg-white transition @error('email') border-rose-500 @enderror" placeholder="Adresse email">
                    @error('email')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div class="relative">
                    <input type="password" name="password" id="password" required autocomplete="current-password"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:bg-white transition @error('password') border-rose-500 @enderror" placeholder="Mot de passe">
                    @error('password')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Options secondaires (Se souvenir de moi / Mot de passe oublié) -->
            <div class="flex items-center justify-between text-xs px-1">
                <label class="flex items-center gap-2 cursor-pointer text-gray-500 select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span>Se souvenir de moi</span>
                </label>
                
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline font-medium">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>

            <!-- Bouton de connexion -->
            <div class="pt-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-600/20 transition active:scale-[0.98] cursor-pointer text-sm">
                    Se connecter de suite
                </button>
            </div>

            <!-- Lien vers l'inscription -->
            <p class="text-center text-xs text-gray-500 pt-2">
                Nouveau sur la plateforme ? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-bold">Créer un compte</a>
            </p>
        </form>
    </div>

</body>
</html>