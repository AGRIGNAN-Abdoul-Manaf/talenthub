@extends('layouts.app', ['title' => $job->title . ' - RecruHub'])

@section('content')
<div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- En-tête de la fiche de poste -->
        <div class="p-8 border-b border-slate-100 bg-slate-50/50">
            <a href="{{ route('jobs.public_index') }}" class="text-sm text-blue-600 hover:underline inline-block mb-4">
                ← Retour aux offres
            </a>
            
            <div class="space-y-3">
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 mb-2">
                            {{ $job->contract_type }}
                        </span>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $job->title }}</h1>
                    </div>
                    {{-- Harmonisation de la vérification du rôle ici --}}
                    @if(auth()->check() && auth()->user()->role === 'candidat')
                        <form action="{{ route('jobs.toggleFavorite', $job->id) }}" method="POST" class="inline shrink-0">
                            @csrf
                            <button type="submit" class="p-3 rounded-2xl border border-slate-200 text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition shadow-sm bg-white" title="Ajouter aux favoris">
                                @if(auth()->user()->favoriteJobs->contains($job->id))
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-600 fill-current" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                    </svg>
                                
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                @endif
                            </button>
                        </form>
                    @endif
                </div>
                <p class="text-lg text-slate-700 font-medium">🏢 {{ $job->company->name ?? 'Entreprise' }}</p>
            </div>
        </div>

        <!-- Détails de l'offre -->
        <div class="p-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm text-slate-600">
                <div>📍 <strong>Lieu :</strong> {{ $job->location }}</div>
                <div>🎓 <strong>Niveau d'études :</strong> {{ $job->education_level }}</div>
                <div>💼 <strong>Expérience :</strong> {{ $job->experience_required }}</div>
                <div>💰 <strong>Salaire :</strong> {{ $job->salary_range ?? 'Non spécifié' }}</div>
            </div>

            <div class="space-y-3">
                <h3 class="text-lg font-bold text-slate-900">Description du poste</h3>
                <div class="text-slate-600 leading-relaxed whitespace-pre-line">
                    {{ $job->description }}
                </div>
            </div>
        </div>

        <!-- Section Action : Postuler -->
        <div class="p-8 border-t border-slate-100 space-y-6">
            <div class="flex justify-between items-center text-xs text-slate-400">
                <span>Publiée le {{ $job->created_at->format('d/m/Y') }}</span>
                <span>ID de l'offre : #{{ $job->id }}</span>
            </div>
            
            @auth
                @if(auth()->user()->role === 'candidat')
                    @php
                        $hasApplied = auth()->user()->applications->where('job_id', $job->id)->first();
                    @endphp

                    @if($hasApplied)
                        <div class="bg-emerald-50 border border-emerald-200 p-5 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="space-y-1">
                                <h4 class="font-bold text-emerald-800 text-base font-sora flex items-center gap-2">
                                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i> Candidature déjà soumise !
                                </h4>
                                <p class="text-xs text-emerald-600 leading-relaxed">
                                    Vous avez déjà postulé à cette offre le <span class="font-semibold">{{ $hasApplied->created_at->format('d/m/Y à H:i') }}</span>.
                                    <br>Statut actuel : <span class="font-bold uppercase tracking-wider text-[11px] bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200 text-emerald-800 inline-block mt-0.5">{{ $hasApplied->status }}</span>
                                </p>
                            </div>
                            <a href="{{ route('candidat.dashboard') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs shadow-md transition shrink-0 flex items-center gap-1">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Mon Espace
                            </a>
                        </div>
                    @else
                        <!-- Formulaire complet de candidature -->
                        <div class="bg-slate-50 border border-slate-200 p-6 rounded-2xl space-y-5">
                            <h3 class="text-lg font-bold text-slate-900 font-sora flex items-center gap-2">
                                <i data-lucide="send" class="w-5 h-5 text-indigo-600"></i> Postuler à cette offre
                            </h3>
                            <p class="text-xs text-slate-500">Transmettez votre dossier directement à l'entreprise <span class="font-semibold">{{ $job->company->name ?? 'Recruteur' }}</span>.</p>

                            <form action="{{ route('jobs.apply', $job->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                                @csrf
                                
                                <!-- CV (Obligatoire) -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 font-sora">
                                        Votre CV (Format PDF, DOC, DOCX - Max 2Mo) <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative border-2 border-dashed border-slate-300 hover:border-indigo-400 rounded-xl p-4 transition bg-white flex items-center gap-3 cursor-pointer">
                                        <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600">
                                            <i data-lucide="file-text" class="w-6 h-6"></i>
                                        </div>
                                        <div class="flex-1 text-left">
                                            <span class="block text-xs font-semibold text-slate-700">Choisir mon fichier de CV</span>
                                            <span class="block text-[10px] text-slate-400">Cliquez ici pour téléverser votre fichier</span>
                                        </div>
                                        <input type="file" name="cv" required class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" onchange="updateFileName(this, 'cv-name')">
                                    </div>
                                    <div id="cv-name" class="text-xs text-emerald-600 font-semibold hidden bg-emerald-50 border border-emerald-100 p-2.5 rounded-lg flex items-center gap-1.5"></div>
                                    @error('cv')
                                        <p class="text-xs text-rose-600 mt-1 font-semibold flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Lettre de motivation (Fichier optionnel) -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 font-sora">
                                        Lettre de motivation (Fichier PDF, DOC, DOCX - Optionnel)
                                    </label>
                                    <div class="relative border-2 border-dashed border-slate-300 hover:border-slate-400 rounded-xl p-4 transition bg-white flex items-center gap-3 cursor-pointer">
                                        <div class="p-3 bg-slate-50 rounded-lg text-slate-500">
                                            <i data-lucide="file-check" class="w-6 h-6"></i>
                                        </div>
                                        <div class="flex-1 text-left">
                                            <span class="block text-xs font-semibold text-slate-700">Choisir ma lettre de motivation</span>
                                            <span class="block text-[10px] text-slate-400">Cliquez ici pour ajouter une lettre en format fichier</span>
                                        </div>
                                        <input type="file" name="cover_letter_file" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" onchange="updateFileName(this, 'letter-name')">
                                    </div>
                                    <div id="letter-name" class="text-xs text-emerald-600 font-semibold hidden bg-emerald-50 border border-emerald-100 p-2.5 rounded-lg flex items-center gap-1.5"></div>
                                    @error('cover_letter_file')
                                        <p class="text-xs text-rose-600 mt-1 font-semibold flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Message de motivation (Texte optionnel) -->
                                <div class="space-y-2">
                                    <label for="cover_letter" class="block text-sm font-bold text-slate-700 font-sora">
                                        Message ou commentaire de motivation (Optionnel)
                                    </label>
                                    <textarea name="cover_letter" id="cover_letter" rows="4" 
                                              class="w-full text-sm bg-white border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder-slate-400 shadow-sm"
                                              placeholder="Écrivez un message court pour vous présenter..."></textarea>
                                    @error('cover_letter')
                                        <p class="text-xs text-rose-600 mt-1 font-semibold flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Bouton d'action -->
                                <div class="pt-2 flex justify-end">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-100 hover:shadow-indigo-200 transition duration-150 text-sm flex items-center gap-2 cursor-pointer">
                                        <i data-lucide="send" class="w-4 h-4"></i> Envoyer ma candidature
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="bg-slate-100 p-4 rounded-xl text-center text-sm text-slate-500 italic font-medium">
                        🛡️ Seuls les candidats connectés peuvent soumettre leur candidature à cette offre.
                    </div>
                @endif
            @else
                <div class="bg-indigo-50 border border-indigo-100 p-6 rounded-2xl flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left">
                    <div>
                        <h4 class="font-bold text-indigo-950 font-sora text-sm">Prêt(e) à relever ce défi ?</h4>
                        <p class="text-xs text-indigo-600 mt-0.5">Connectez-vous à votre espace candidat pour postuler en quelques instants.</p>
                    </div>
                    <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs shadow-md shadow-indigo-100 hover:shadow-indigo-200 transition duration-150 shrink-0">
                        Se connecter et postuler
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection

{{-- Déplacement des scripts dans une stack dédiée (très recommandé sous Laravel) --}}
@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    function updateFileName(input, targetId) {
        const target = document.getElementById(targetId);
        if (input.files && input.files.length > 0) {
            target.innerHTML = `<i data-lucide="check" class="w-3.5 h-3.5"></i> Fichier sélectionné : <span class="text-slate-800 underline font-normal ml-1">${input.files[0].name}</span>`;
            target.classList.remove('hidden');
            lucide.createIcons();
        } else {
            target.textContent = '';
            target.classList.add('hidden');
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endpush