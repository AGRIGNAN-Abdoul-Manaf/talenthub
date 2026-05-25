@extends('layouts.app', ['title' => 'Dossier RH - ' . $application->user->name . ' - RecruHub'])

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>

<div class="min-h-screen bg-slate-50 flex">
    <!-- Sidebar Recruteur -->
    <aside class="w-64 bg-slate-950 text-white flex flex-col justify-between p-6 shrink-0 hidden lg:flex">
        <div class="space-y-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center font-bold text-white font-sora">R</div>
                <span class="text-lg font-bold font-sora tracking-wide">RecruHub <span class="text-xs text-indigo-400 block font-normal">Pro</span></span>
            </div>

            <div class="bg-slate-900 p-3 rounded-xl flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center font-bold text-sm text-white">
                    {{ substr(auth()->user()->name ?? 'RE', 0, 2) }}
                </div>
                <div>
                    <h4 class="text-xs font-bold truncate">{{ auth()->user()->name ?? 'Entreprise' }}</h4>
                    <span class="text-[10px] text-emerald-400 font-semibold uppercase tracking-wider">Recruteur</span>
                </div>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('recruteur.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 bg-indigo-600 rounded-xl text-sm font-medium text-white transition">
                    <i data-lucide="briefcase" class="w-4 h-4"></i> Candidatures reçues
                </a>
                <a href="{{ route('jobs.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl text-sm font-medium transition">
                    <i data-lucide="list" class="w-4 h-4"></i> Mes Offres
                </a>
                <a href="{{ route('jobs.create') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl text-sm font-medium transition">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Publier une offre
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

        <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-red-400 hover:bg-red-950/30 rounded-xl text-sm font-medium transition w-full text-left cursor-pointer border-0 bg-transparent">
                <i data-lucide="log-out" class="w-4 h-4"></i> Se déconnecter
            </button>
        </form>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-8 overflow-y-auto">
        
        <!-- Header -->
        <header class="mb-8">
            <a href="{{ route('recruteur.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 transition mb-4">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour au tableau de bord
            </a>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 font-sora">Dossier Candidat - {{ $application->user->name }}</h1>
                    <p class="text-sm text-slate-500">Postule pour l'offre : <span class="font-bold text-slate-700">{{ $application->job->title }}</span></p>
                </div>
                
                @php
                    $statusColors = [
                        'En attente' => 'bg-amber-50 text-amber-800 border-amber-200',
                        'Acceptée' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                        'Refusée' => 'bg-rose-50 text-rose-800 border-rose-200',
                        'Entretien programmé' => 'bg-blue-50 text-blue-800 border-blue-200',
                    ];
                    $colorClass = $statusColors[$application->status] ?? 'bg-slate-50 text-slate-800 border-slate-200';
                @endphp
                <div class="flex items-center gap-3 flex-wrap">
                    <a href="{{ route('recruteur.applications.pdf', $application->id) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl text-sm shadow-md hover:shadow-lg transition flex items-center gap-1.5 border border-indigo-500">
                        <i data-lucide="file-text" class="w-4 h-4"></i> Exporter Dossier RH (PDF)
                    </a>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold border {{ $colorClass }} shadow-sm">
                        Statut actuel : {{ $application->status }}
                    </span>
                </div>
            </div>
        </header>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- COLUMN 1 & 2 : CANDIDATE DOSSIER (Col span 2) -->
            <div class="xl:col-span-2 space-y-6">
                
                <!-- 👤 BLOC : DONNÉES DU CANDIDAT -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
                    <h3 class="text-lg font-bold text-slate-900 font-sora flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i data-lucide="user" class="w-5 h-5 text-indigo-600"></i> Informations Profil
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Nom Complet</span>
                            <span class="text-slate-800 font-semibold block text-base">{{ $application->user->name }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Adresse Email</span>
                            <a href="mailto:{{ $application->user->email }}" class="text-blue-600 hover:underline font-semibold block text-base">{{ $application->user->email }}</a>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Numéro de Téléphone</span>
                            <span class="text-slate-800 font-semibold block text-base">{{ $application->user->phone ?? 'Non renseigné' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Compétences déclarées</span>
                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                @forelse(explode(',', $application->user->skills ?? '') as $skill)
                                    @if(trim($skill))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100">{{ trim($skill) }}</span>
                                    @endif
                                @empty
                                    <span class="text-slate-400 italic">Aucune compétence mentionnée</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <span class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Résumé / Biographie</span>
                        <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line bg-slate-50 p-4 rounded-xl border border-slate-200/50">
                            {{ $application->user->bio ?? 'Le candidat n' . "'a pas fourni de résumé ou biographie." }}
                        </p>
                    </div>
                </div>

                <!-- 📂 BLOC : DOCUMENTS JOINTS -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <h3 class="text-lg font-bold text-slate-900 font-sora flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i data-lucide="paperclip" class="w-5 h-5 text-indigo-600"></i> Pièces Justificatives
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-slate-50 border border-slate-200/60 p-4 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 bg-rose-50 text-rose-600 rounded-lg">
                                    <i data-lucide="file-text" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-800 text-sm">Curriculum Vitae (CV)</span>
                                    <span class="block text-[10px] text-slate-400">Document principal</span>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold p-2 rounded-xl text-xs shadow-sm transition">
                                <i data-lucide="download" class="w-4 h-4"></i>
                            </a>
                        </div>

                        @if($application->cover_letter_path)
                            <div class="bg-slate-50 border border-slate-200/60 p-4 rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 bg-blue-50 text-blue-600 rounded-lg">
                                        <i data-lucide="file-check" class="w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-slate-800 text-sm">Lettre de motivation</span>
                                        <span class="block text-[10px] text-slate-400 font-medium">Document complémentaire</span>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $application->cover_letter_path) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold p-2 rounded-xl text-xs shadow-sm transition">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                </a>
                            </div>
                        @endif
                    </div>

                    @if($application->cover_letter)
                        <div class="pt-4 border-t border-slate-100">
                            <span class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Message d'accompagnement</span>
                            <p class="text-slate-600 text-xs leading-relaxed whitespace-pre-line bg-slate-50 p-4 rounded-xl border border-slate-200/50">
                                {{ $application->cover_letter }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- 📝 BLOC : NOTES RH INTERNES PRIVÉES -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="text-lg font-bold text-slate-900 font-sora flex items-center gap-2">
                            <i data-lucide="sticky-note" class="w-5 h-5 text-indigo-600"></i> Évaluation RH & Notes Internes
                        </h3>
                        <span class="bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">
                            Privé • Invisible au candidat
                        </span>
                    </div>

                    <form action="{{ route('recruteur.applications.saveNotes', $application->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <textarea name="recruiter_notes" rows="6" placeholder="Saisissez vos observations sur ce candidat (ex: points forts, points faibles, prétentions salariales, avis technique...)" class="w-full text-xs bg-slate-50 border border-slate-200 focus:bg-white rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder-slate-400">{{ $application->recruiter_notes }}</textarea>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs shadow transition duration-150 cursor-pointer">
                                Enregistrer les notes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- COLUMN 3 : MESSAGING & INTERVIEWS PLANNING -->
            <div class="space-y-6">
                
                <!-- 📅 BLOC : PLANIFICATION D'ENTRETIEN -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <h3 class="text-base font-bold text-slate-900 font-sora flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i data-lucide="calendar" class="w-5 h-5 text-indigo-600"></i> Planifier un Entretien
                    </h3>
                    
                    <form action="{{ route('recruteur.applications.scheduleInterview', $application->id) }}" method="POST" class="space-y-4 text-xs">
                        @csrf
                        
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Date de rencontre <span class="text-rose-500">*</span></label>
                            <input type="date" name="interview_date" value="{{ $application->interview_date ? $application->interview_date->format('Y-m-d') : '' }}" required min="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Heure de rencontre <span class="text-rose-500">*</span></label>
                            <input type="time" name="interview_time" value="{{ $application->interview_time }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Lieu / Lien Visioconférence <span class="text-rose-500">*</span></label>
                            <input type="text" name="interview_location" value="{{ $application->interview_location }}" required placeholder="Ex: Lien Zoom, Teams ou Bureaux à Paris" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Consignes complémentaires</label>
                            <textarea name="interview_details" rows="3" placeholder="Ex: Prévoir un portfolio, tests de programmation, etc." class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ $application->interview_details }}</textarea>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl transition duration-150 cursor-pointer shadow-md">
                            📆 Confirmer et programmer
                        </button>
                    </form>
                </div>

                <!-- 💬 BLOC : MESSAGERIE DU CANDIDAT (CHAT BOX) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col h-[400px]">
                    <h3 class="text-base font-bold text-slate-900 font-sora flex items-center gap-2 border-b border-slate-100 pb-3 shrink-0">
                        <i data-lucide="message-circle" class="w-5 h-5 text-indigo-600"></i> Contacter Candidat
                    </h3>

                    <!-- Message Feed -->
                    <div class="flex-1 overflow-y-auto my-4 p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-3 flex flex-col">
                        @forelse($application->messages ?? [] as $msg)
                            @if($msg['sender'] === 'system')
                                <div class="self-center bg-slate-200 text-slate-700 text-[9px] font-bold px-2.5 py-0.5 rounded-full text-center max-w-[90%]">
                                    {{ $msg['text'] }}
                                </div>
                            @else
                                <div class="flex flex-col {{ $msg['sender'] === 'recruteur' ? 'items-end self-end' : 'items-start self-start' }} max-w-[85%]">
                                    <span class="text-[8px] font-semibold text-slate-400 mb-0.5 px-1">
                                        {{ $msg['sender'] === 'recruteur' ? 'Vous' : $application->user->name }}
                                    </span>
                                    <div class="p-2.5 rounded-2xl text-[11px] {{ $msg['sender'] === 'recruteur' ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-slate-800 rounded-tl-none border border-slate-200 shadow-sm' }}">
                                        {{ $msg['text'] }}
                                    </div>
                                    <span class="text-[7px] text-slate-400 mt-1 px-1">
                                        {{ date('d/m H:i', strtotime($msg['created_at'])) }}
                                    </span>
                                </div>
                            @endif
                        @empty
                            <div class="flex-1 flex flex-col items-center justify-center text-center text-slate-400 py-10">
                                <i data-lucide="message-square-off" class="w-8 h-8 text-slate-300 mb-2"></i>
                                <p class="text-xs font-semibold">Aucun message échangé.</p>
                                <p class="text-[10px] text-slate-400">Écrivez un message ci-dessous pour démarrer l'échange.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Envoi Form -->
                    <form action="{{ route('recruteur.applications.sendMessage', $application->id) }}" method="POST" class="flex gap-2 shrink-0">
                        @csrf
                        <input type="text" name="message" required placeholder="Tapez votre message..." class="flex-1 text-xs px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-xl flex items-center justify-center shrink-0 border-0 cursor-pointer shadow-sm">
                            <i data-lucide="send" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>
</div>

<script>lucide.createIcons();</script>
@endsection
