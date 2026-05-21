<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentHub - Création de compte</title>
    <!-- On utilise la même configuration Tailwind -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Ajout d'une police plus moderne pour l'aspect Mobile App -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <!-- Conteneur style Application Mobile (Max-width serré et ombres douces) -->
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
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Créer un compte</h1>
            <p class="text-xs text-gray-400 mt-1">Rejoignez la communauté TalentHub</p>
        </div>

        <!-- Formulaire -->
        <form action="{{ route('register') }}" method="POST" class="px-8 pb-8 space-y-5 flex-grow">
            @csrf

            <!-- 📱 SELECTEUR DE RÔLE (Format Boutons Tactiles) -->
            <div class="space-y-2">
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Vous êtes ?</span>
                <div class="grid grid-cols-2 gap-3">
                    
                    <!-- Option Candidat -->
                    <label class="relative flex flex-col items-center justify-center p-4 border rounded-2xl cursor-pointer transition-all duration-200 bg-gray-50/50 border-gray-200 group" id="label-candidat">
                        <input type="radio" name="role" value="candidat" class="sr-only" checked onchange="updateMobileStyle()">
                        
                        <!-- Icône SVG Candidat (Profil) -->
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm text-gray-500 group-hover:text-blue-600 transition" id="icon-box-candidat">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        
                        <span class="block text-sm font-bold text-gray-800 mt-2">Candidat</span>
                    </label>

                    <!-- Option Recruteur -->
                    <label class="relative flex flex-col items-center justify-center p-4 border rounded-2xl cursor-pointer transition-all duration-200 bg-gray-50/50 border-gray-200 group" id="label-recruteur">
                        <input type="radio" name="role" value="recruteur" class="sr-only" onchange="updateMobileStyle()">
                        
                        <!-- Icône SVG Recruteur (Mallette) -->
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm text-gray-500 group-hover:text-blue-600 transition" id="icon-box-recruteur">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        
                        <span class="block text-sm font-bold text-gray-800 mt-2">Recruteur</span>
                    </label>

                </div>
                @error('role')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- CHOMPS DE SAISIE (Style Épuré Input Mobile) -->
            <div class="space-y-4">
                
                <!-- Nom Complet -->
                <div class="relative">
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="name"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:bg-white transition @error('name') border-rose-500 @enderror" placeholder="Nom complet ou Entreprise">
                    @error('name')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="relative">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:bg-white transition @error('email') border-rose-500 @enderror" placeholder="Adresse email">
                    @error('email')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div class="relative">
                    <input type="password" name="password" id="password" required autocomplete="new-password"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:bg-white transition @error('password') border-rose-500 @enderror" placeholder="Mot de passe">
                    @error('password')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmation Mot de passe -->
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:bg-white transition" placeholder="Confirmer le mot de passe">
                </div>
            </div>

            <!-- Bouton d'action principal -->
            <div class="pt-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-600/20 transition active:scale-[0.98] cursor-pointer text-sm">
                    S'inscrire instantanément
                </button>
            </div>

            <!-- Footer d'authentification -->
            <p class="text-center text-xs text-gray-500 pt-2">
                Déjà un compte ? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-bold">Se connecter</a>
            </p>
        </form>
    </div>

    <!-- Script JavaScript pour basculer les styles de focus "Mobile App" -->
    <script>
        function updateMobileStyle() {
            const candidatRadio = document.querySelector('input[value="candidat"]');
            const recruteurRadio = document.querySelector('input[value="recruteur"]');
            
            const labelCandidat = document.getElementById('label-candidat');
            const labelRecruteur = document.getElementById('label-recruteur');
            
            const iconCandidat = document.getElementById('icon-box-candidat');
            const iconRecruteur = document.getElementById('icon-box-recruteur');

            if (candidatRadio.checked) {
                // Style Actif pour Candidat
                labelCandidat.classList.add('border-blue-500', 'bg-blue-50/30');
                iconCandidat.classList.add('bg-blue-600', 'text-white');
                iconCandidat.classList.remove('bg-white', 'text-gray-500');

                // Style Inactif pour Recruteur
                labelRecruteur.classList.remove('border-blue-500', 'bg-blue-50/30');
                iconRecruteur.classList.add('bg-white', 'text-gray-500');
                iconRecruteur.classList.remove('bg-blue-600', 'text-white');
            } else if (recruteurRadio.checked) {
                // Style Actif pour Recruteur
                labelRecruteur.classList.add('border-blue-500', 'bg-blue-50/30');
                iconRecruteur.classList.add('bg-blue-600', 'text-white');
                iconRecruteur.classList.remove('bg-white', 'text-gray-500');

                // Style Inactif pour Candidat
                labelCandidat.classList.remove('border-blue-500', 'bg-blue-50/30');
                iconCandidat.classList.add('bg-white', 'text-gray-500');
                iconCandidat.classList.remove('bg-blue-600', 'text-white');
            }
        }

        document.addEventListener('DOMContentLoaded', updateMobileStyle);
    </script>

</body>
</html>