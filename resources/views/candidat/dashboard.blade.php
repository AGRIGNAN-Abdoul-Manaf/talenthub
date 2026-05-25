@extends('layouts.app', ['title' => 'Espace Candidat - RecruHub'])

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>

<div class="min-h-screen bg-slate-50 flex">
    <!-- Sidebar Candidat -->
    <aside class="w-64 bg-slate-950 text-white flex flex-col justify-between p-6 shrink-0 hidden lg:flex">
        <div class="space-y-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center font-bold text-white font-sora">R</div>
                <span class="text-lg font-bold font-sora tracking-wide">RecruHub</span>
            </div>

            <!-- Profil -->
            <div class="bg-slate-900 p-3 rounded-xl flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center font-bold text-sm text-white">
                    {{ substr(auth()->user()->name ?? 'CA', 0, 2) }}
                </div>
                <div>
                    <h4 class="text-xs font-bold truncate">{{ auth()->user()->name ?? 'Candidat' }}</h4>
                    <span class="text-[10px] text-indigo-400 font-semibold uppercase tracking-wider">Candidat</span>
                </div>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('candidat.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 bg-blue-600 rounded-xl text-sm font-medium text-white transition">
                    <i data-lucide="home" class="w-4 h-4"></i> Mon Espace
                </a>
                <a href="{{ route('jobs.public_index') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl text-sm font-medium transition">
                    <i data-lucide="search" class="w-4 h-4"></i> Rechercher
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl text-sm font-medium transition">
                    <i data-lucide="user" class="w-4 h-4"></i> Mon Profil
                </a>
                <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl text-sm font-medium transition">
                    <i data-lucide="bell" class="w-4 h-4"></i> Notifications
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
            </nav>
        </div>

        <!-- Déconnexion -->
        <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-red-400 hover:bg-red-950/30 rounded-xl text-sm font-medium transition w-full text-left cursor-pointer border-0 bg-transparent">
                <i data-lucide="log-out" class="w-4 h-4"></i> Se déconnecter
            </button>
        </form>
    </aside>

    <!-- Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-sora">Bienvenue, {{ auth()->user()->name }}</h1>
                <p class="text-sm text-slate-500">Suivez l'état d'avancement de vos candidatures et de vos entretiens.</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('candidat.cv.pdf') }}" target="_blank" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2.5 px-5 rounded-xl text-sm shadow-md hover:shadow-lg transition flex items-center gap-1.5 border border-slate-750">
                    <i data-lucide="file-text" class="w-4 h-4"></i> Télécharger mon CV (PDF)
                </a>
                <a href="{{ route('jobs.public_index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-xl text-sm shadow-md hover:shadow-lg transition flex items-center gap-1.5">
                    <i data-lucide="search" class="w-4 h-4"></i> Explorer les offres
                </a>
            </div>
        </header>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- COLONNE PRINCIPALE : CANDIDATURES (Col span 2) -->
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 font-sora flex items-center gap-2">
                        <i data-lucide="send" class="w-5 h-5 text-blue-600"></i> Vos candidatures en cours
                    </h3>
                    
                    <div class="space-y-4">
                        @forelse($applications as $application)
                            <div class="border border-slate-150 rounded-2xl overflow-hidden shadow-sm bg-white hover:border-slate-300 transition">
                                
                                <!-- En-tête de carte -->
                                <div class="p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-xl border border-slate-100 bg-white shadow-sm flex items-center justify-center p-1 text-lg font-bold font-sora text-slate-800 shrink-0">
                                            {{ substr($application->job->company->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900 text-base leading-tight">{{ $application->job->title }}</h4>
                                            <div class="flex flex-wrap gap-2 items-center text-xs text-slate-500 mt-1">
                                                <span class="font-semibold text-slate-700">{{ $application->job->company->name ?? 'Entreprise' }}</span>
                                                <span>•</span>
                                                <span>📍 {{ $application->job->location }}</span>
                                                <span>•</span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-blue-50 text-blue-700 border-blue-100">
                                                    {{ $application->job->contract_type }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-3 sm:pt-0">
                                        <div class="flex flex-col items-start sm:items-end">
                                            @php
                                                $statusColors = [
                                                    'En attente' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                    'Acceptée' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                                    'Refusée' => 'bg-rose-50 text-rose-800 border-rose-200',
                                                    'Entretien programmé' => 'bg-blue-50 text-blue-800 border-blue-200',
                                                ];
                                                $colorClass = $statusColors[$application->status] ?? 'bg-slate-50 text-slate-800 border-slate-200';
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $colorClass }}">
                                                ● {{ $application->status }}
                                            </span>
                                            <span class="text-[10px] text-slate-400 mt-1">
                                                Postulé {{ $application->created_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-1.5 ml-2 shrink-0">
                                            <!-- Bouton Discussion -->
                                            <button onclick="toggleDetails('details-{{ $application->id }}')" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-xl transition cursor-pointer relative border-0 bg-transparent" title="Détails & Chat">
                                                <i data-lucide="message-square" class="w-4 h-4"></i>
                                                @if($application->messages && count($application->messages) > 0)
                                                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white"></span>
                                                @endif
                                            </button>

                                            <!-- Annuler candidature -->
                                            <form action="{{ route('applications.cancel', $application) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette candidature ? Vos fichiers (CV et lettre de motivation) seront supprimés du serveur.')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition cursor-pointer border-0 bg-transparent" title="Annuler ma candidature">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Détails & Chat Déroulant -->
                                <div id="details-{{ $application->id }}" class="hidden border-t border-slate-100 bg-slate-50/50 p-6 space-y-6">
                                    
                                    <!-- Si entretien planifié -->
                                    @if($application->status === 'Entretien programmé' && $application->interview_date)
                                        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-5 space-y-3">
                                            <h5 class="font-bold text-indigo-950 font-sora text-sm flex items-center gap-2">
                                                <i data-lucide="calendar" class="w-5 h-5 text-indigo-600 animate-pulse"></i>
                                                Détails de l'entretien programmé
                                            </h5>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-700">
                                                <div>
                                                    <span class="block text-[10px] uppercase font-bold text-slate-400">Date et heure</span>
                                                    <strong class="text-sm font-semibold text-slate-900">Le {{ $application->interview_date->format('d/m/Y') }} à {{ $application->interview_time }}</strong>
                                                </div>
                                                <div>
                                                    <span class="block text-[10px] uppercase font-bold text-slate-400">Lieu / Lien de rencontre</span>
                                                    @if(filter_var($application->interview_location, FILTER_VALIDATE_URL))
                                                        <a href="{{ $application->interview_location }}" target="_blank" class="text-blue-600 hover:underline font-bold inline-flex items-center gap-1">
                                                            Rejoindre la réunion <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                                        </a>
                                                    @else
                                                        <strong class="text-slate-900 font-semibold">{{ $application->interview_location }}</strong>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($application->interview_details)
                                                <div class="pt-2 border-t border-indigo-100 text-xs text-slate-600">
                                                    <span class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Consignes du recruteur</span>
                                                    <p class="whitespace-pre-line bg-white p-3 rounded-lg border border-indigo-100/50">{{ $application->interview_details }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Espace Chat -->
                                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 space-y-4">
                                        <h5 class="font-bold text-slate-800 text-sm font-sora flex items-center gap-2">
                                            <i data-lucide="message-circle" class="w-4 h-4 text-slate-600"></i>
                                            Discussion avec le Recruteur
                                        </h5>
                                        
                                        <div class="h-64 overflow-y-auto border border-slate-100 rounded-xl p-4 bg-slate-50 space-y-3 flex flex-col">
                                            @forelse($application->messages ?? [] as $msg)
                                                @if($msg['sender'] === 'system')
                                                    <div class="self-center bg-slate-200 text-slate-700 text-[10px] font-bold px-3 py-1 rounded-full text-center max-w-md border border-slate-300/40">
                                                        {{ $msg['text'] }}
                                                    </div>
                                                @else
                                                    <div class="flex flex-col {{ $msg['sender'] === 'candidat' ? 'items-end self-end' : 'items-start self-start' }} max-w-[80%]">
                                                        <span class="text-[9px] font-semibold text-slate-400 mb-0.5 px-1">
                                                            {{ $msg['sender'] === 'candidat' ? 'Vous' : ($application->job->company->name ?? 'Recruteur') }}
                                                        </span>
                                                        <div class="p-3 rounded-2xl text-xs {{ $msg['sender'] === 'candidat' ? 'bg-blue-600 text-white rounded-tr-none shadow-blue-500/10' : 'bg-white text-slate-800 rounded-tl-none border border-slate-200 shadow-sm' }}">
                                                            {{ $msg['text'] }}
                                                        </div>
                                                        <span class="text-[8px] text-slate-400 mt-1 px-1">
                                                            {{ date('d/m H:i', strtotime($msg['created_at'])) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            @empty
                                                <div class="flex-1 flex flex-col items-center justify-center text-center text-slate-400 py-10">
                                                    <i data-lucide="message-square-off" class="w-8 h-8 text-slate-300 mb-2"></i>
                                                    <p class="text-xs font-semibold">Aucun message pour le moment.</p>
                                                    <p class="text-[10px] text-slate-400">Répondez à la discussion ou posez vos questions.</p>
                                                </div>
                                            @endforelse
                                        </div>

                                        <!-- Formulaire de réponse -->
                                        <form action="{{ route('candidat.applications.replyMessage', $application->id) }}" method="POST" class="flex gap-2">
                                            @csrf
                                            <input type="text" name="message" required placeholder="Votre message..." class="flex-1 text-xs px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-2.5 rounded-xl text-xs font-semibold shadow flex items-center justify-center shrink-0 cursor-pointer border-0">
                                                <i data-lucide="send" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 text-sm text-slate-400">
                                <i data-lucide="send" class="w-10 h-10 mx-auto mb-3 text-slate-300"></i>
                                <p class="font-medium text-slate-500">Vous n'avez pas encore postulé à des offres.</p>
                                <p class="text-xs text-slate-400 mt-1">Découvrez des opportunités et soumettez votre profil.</p>
                                <a href="{{ route('jobs.public_index') }}" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-xl text-xs shadow-md transition">
                                    Explorer les offres
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- COLONNE LATÉRALE : FAVORIS & RÉSUMÉ DES ENTRETIENS -->
            <div class="space-y-6">
                
                <!-- BLOC : ENTRETIENS PLANIFIÉS -->
                <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-2xl shadow-xl text-white p-6 border border-slate-800">
                    <h3 class="text-base font-bold mb-4 font-sora flex items-center gap-2">
                        <i data-lucide="calendar" class="w-5 h-5 text-indigo-400"></i> Entretiens Planifiés
                    </h3>
                    <div class="space-y-3">
                        @forelse($interviews as $interview)
                            <div class="bg-white/10 hover:bg-white/15 backdrop-blur-md rounded-xl p-3.5 border border-white/5 space-y-2 transition">
                                <h4 class="text-xs font-bold text-indigo-200 truncate">{{ $interview->job->title }}</h4>
                                <p class="text-[10px] text-slate-300 font-medium">{{ $interview->job->company->name ?? 'Recruteur' }}</p>
                                <div class="flex items-center justify-between pt-1 text-[10px] text-white">
                                    <span class="font-semibold bg-indigo-500/30 px-2 py-0.5 rounded border border-indigo-500/20">📅 Le {{ $interview->interview_date->format('d/m') }}</span>
                                    <span class="font-semibold bg-emerald-500/30 px-2 py-0.5 rounded border border-emerald-500/20">🕒 À {{ $interview->interview_time }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">Aucun entretien planifié pour le moment.</p>
                        @endforelse
                    </div>
                </div>

                <!-- BLOC : OFFRES ENREGISTRÉES (FAVORIS) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-base font-bold text-slate-900 mb-4 font-sora flex items-center gap-2">
                        <i data-lucide="bookmark" class="w-5 h-5 text-rose-500"></i> Offres Favoris
                    </h3>
                    <div class="space-y-4">
                        @forelse($favoriteJobs as $job)
                            <div class="flex justify-between items-center p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition gap-2">
                                <div class="truncate">
                                    <h4 class="font-bold text-xs text-slate-800 truncate">{{ $job->title }}</h4>
                                    <span class="text-[10px] text-slate-500 truncate block">{{ $job->company->name }} • {{ $job->location }}</span>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    <a href="{{ route('jobs.show', $job->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Voir l'offre">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('jobs.toggleFavorite', $job->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition cursor-pointer border-0 bg-transparent" title="Retirer">
                                            <i data-lucide="bookmark-x" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">Aucune offre enregistrée dans vos favoris.</p>
                        @endforelse
                    </div>
                </div>
                
            </div>
            
        </div>
    </main>
</div>

<script>
    function toggleDetails(id) {
        const details = document.getElementById(id);
        if (details.classList.contains('hidden')) {
            details.classList.remove('hidden');
        } else {
            details.classList.add('hidden');
        }
    }
    lucide.createIcons();
</script>
@endsection