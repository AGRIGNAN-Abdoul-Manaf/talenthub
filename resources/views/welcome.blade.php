<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentHub - Accueil</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- 🌐 BARRE DE NAVIGATION (Navbar) -->
    <nav class="bg-white shadow-sm border-b border-gray-200 w-full px-6 py-4 z-20 relative">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <!-- Logo / Nom du site -->
            <a href="{{ route('home') }}" class="text-2xl font-black text-blue-600 tracking-tight">
                TalentHub
            </a>

            <!-- Liens d'accès et d'authentification -->
            <div class="flex items-center space-x-4">
                <!-- Lien public vers les offres accessible à tous -->
                <a href="{{ route('jobs.public_index') }}" class="text-gray-600 hover:text-blue-600 font-medium transition">
                    Voir les offres
                </a>

                @if (Route::has('login'))
                    <span class="text-gray-300">|</span>
                    
                    @auth
                        <!-- Si connecté : On affiche le bouton du tableau de bord correspondant au rôle -->
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition shadow-sm text-sm">
                                Mon Tableau de Bord
                            </a>
                        @elseif(auth()->user()->role === 'recruteur')
                            <a href="{{ route('recruteur.dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition shadow-sm text-sm">
                                Espace Recruteur
                            </a>
                        @else
                            <a href="{{ route('candidat.dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition shadow-sm text-sm">
                                Mon Espace Candidat
                            </a>
                        @endif

                        <!-- Bouton Déconnexion rapide -->
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-rose-600 text-sm font-medium transition cursor-pointer">
                                Déconnexion
                            </button>
                        </form>
                    @else
                        <!-- Si visiteur anonyme : Boutons Connexion & Inscription -->
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-medium transition text-sm">
                            Se connecter
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition shadow-sm text-sm">
                                S'inscrire
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- 🎯 CONTENU CENTRAL HERO AVEC BACKGROUND DÉROULANT ANIMÉ -->
    <div class="flex-grow relative overflow-hidden flex items-center justify-center">
        
        <!-- 1. Le Slider en arrière-plan -->
        <div id="hero-slider" class="absolute inset-0 w-full h-full z-0">
            <!-- Image 1 -->
            <div class="slide absolute inset-0 w-full h-full bg-cover bg-center transition-opacity duration-1000 opacity-100" 
                 style="background-image: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75)), url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1920&q=80');">
            </div>
            <!-- Image 2 -->
            <div class="slide absolute inset-0 w-full h-full bg-cover bg-center transition-opacity duration-1000 opacity-0" 
                 style="background-image: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75)), url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1920&q=80');">
            </div>
            <!-- Image 3 -->
            <div class="slide absolute inset-0 w-full h-full bg-cover bg-center transition-opacity duration-1000 opacity-0" 
                 style="background-image: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75)), url('https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1920&q=80');">
            </div>
        </div>

        <!-- 2. Les Textes et Boutons (Au-dessus des images grâce au z-10) -->
        <div class="relative z-10 text-center max-w-3xl mx-auto px-6 py-20">
            <span class="inline-block bg-blue-500/20 text-blue-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-blue-500/30">
                Propulsez votre avenir
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 mt-4 tracking-tight leading-tight">
                Bienvenue sur <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">TalentHub</span>
            </h1>
            <p class="text-gray-300 text-lg md:text-xl mb-10 max-w-xl mx-auto leading-relaxed">
                La plateforme moderne de mise en relation directe entre Candidats talentueux et Recruteurs exigeants.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('jobs.public_index') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-4 rounded-xl transition shadow-lg shadow-blue-600/20 transform hover:-translate-y-0.5">
                    Explorer les opportunités
                </a>
                
                @guest
                    <a href="{{ route('register') }}" class="w-full sm:w-auto bg-white/10 hover:bg-white/20 text-white font-semibold px-8 py-4 rounded-xl backdrop-blur-sm border border-white/20 transition">
                        Créer un compte
                    </a>
                @endguest
            </div>
        </div>
    </div>

    <!-- 3. Script JS d'animation du fondu enchaîné -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('#hero-slider .slide');
            let currentSlide = 0;
            
            // Alterne l'affichage toutes les 5 secondes
            setInterval(function() {
                slides[currentSlide].classList.remove('opacity-100');
                slides[currentSlide].classList.add('opacity-0');
                
                currentSlide = (currentSlide + 1) % slides.length;
                
                slides[currentSlide].classList.remove('opacity-0');
                slides[currentSlide].classList.add('opacity-100');
            }, 5000);
        });
    </script>

</body>
</html>