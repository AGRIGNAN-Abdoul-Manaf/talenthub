@extends('layouts.app', ['title' => 'Mes Notifications - RecruHub'])

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>

<div class="min-h-screen {{ auth()->user()->role === 'admin' ? 'bg-slate-900 text-slate-100' : 'bg-slate-50 text-slate-900' }} flex">
    
    <!-- ========================================== -->
    <!-- 🎛️ SIDEBAR DYNAMIQUE PAR RÔLE -->
    <!-- ========================================== -->
    
    @if(auth()->user()->role === 'admin')
        <!-- Sidebar Admin -->
        <aside class="w-64 bg-slate-950 text-white flex flex-col justify-between p-6 shrink-0 hidden lg:flex border-r border-slate-800">
            <div class="space-y-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center font-bold text-white font-sora">A</div>
                    <span class="text-lg font-bold font-sora tracking-wide">RecruHub <span class="text-xs text-rose-500 block font-normal">Admin</span></span>
                </div>

                <!-- Profil Admin -->
                <div class="bg-slate-900 p-3 rounded-xl flex items-center gap-3 border border-slate-800">
                    <div class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center font-bold text-sm text-slate-300">
                        {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                    </div>
                    <div>
                        <h4 class="text-xs font-bold truncate">{{ auth()->user()->name ?? 'Administrateur' }}</h4>
                        <span class="text-[10px] text-rose-400 font-semibold uppercase tracking-wider">Admin</span>
                    </div>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl text-sm font-medium transition">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Statistiques
                    </a>
                    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl text-sm font-medium transition">
                        <i data-lucide="users" class="w-4 h-4"></i> Utilisateurs
                    </a>
                    <a href="{{ route('admin.companies') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl text-sm font-medium transition">
                        <i data-lucide="building" class="w-4 h-4"></i> Entreprises
                    </a>
                    <a href="{{ route('admin.jobs') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl text-sm font-medium transition">
                        <i data-lucide="briefcase" class="w-4 h-4"></i> Offres d'emploi
                    </a>
                    <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm font-medium text-white transition">
                        <i data-lucide="bell" class="w-4 h-4"></i> Notifications
                        @if($unreadCount > 0)
                            <span class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                </nav>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-rose-400 hover:bg-rose-950/30 rounded-xl text-sm font-medium transition w-full text-left cursor-pointer border-0 bg-transparent">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Se déconnecter
                </button>
            </form>
        </aside>

    @elseif(auth()->user()->role === 'recruteur')
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
                    <a href="{{ route('recruteur.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl text-sm font-medium transition">
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
                    <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-2.5 bg-indigo-600 rounded-xl text-sm font-medium text-white transition">
                        <i data-lucide="bell" class="w-4 h-4"></i> Notifications
                        @if($unreadCount > 0)
                            <span class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                {{ $unreadCount }}
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

    @else
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
                    <a href="{{ route('candidat.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl text-sm font-medium transition">
                        <i data-lucide="home" class="w-4 h-4"></i> Mon Espace
                    </a>
                    <a href="{{ route('jobs.public_index') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl text-sm font-medium transition">
                        <i data-lucide="search" class="w-4 h-4"></i> Rechercher
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl text-sm font-medium transition">
                        <i data-lucide="user" class="w-4 h-4"></i> Mon Profil
                    </a>
                    <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-2.5 bg-blue-600 rounded-xl text-sm font-medium text-white transition">
                        <i data-lucide="bell" class="w-4 h-4"></i> Notifications
                        @if($unreadCount > 0)
                            <span class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                {{ $unreadCount }}
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
    @endif

    <!-- ========================================== -->
    <!-- 📄 CONTENU PRINCIPAL DE LA PAGE -->
    <!-- ========================================== -->
    <main class="flex-1 p-8 overflow-y-auto">
        
        <!-- En-tête -->
        <header class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold font-sora">Centre de Notifications</h1>
                <p class="text-sm {{ auth()->user()->role === 'admin' ? 'text-slate-400' : 'text-slate-500' }}">
                    Restez informé des dernières mises à jour de votre compte, candidatures et entretiens.
                </p>
            </div>
            
            @if($unreadCount > 0)
                <form action="{{ route('notifications.read_all') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl text-sm shadow-md hover:shadow-lg transition flex items-center gap-2 cursor-pointer border-0">
                        <i data-lucide="check-check" class="w-4 h-4"></i>
                        Tout marquer comme lu
                    </button>
                </form>
            @endif
        </header>

        <!-- Liste des notifications -->
        <div class="max-w-4xl space-y-4">
            @forelse($notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $data = $notification->data;
                    $type = $data['type'] ?? 'default';
                    
                    // Configurations graphiques par type
                    switch($type) {
                        case 'registration':
                            $icon = 'user';
                            $iconColor = 'text-blue-600';
                            $bgColor = auth()->user()->role === 'admin' ? 'bg-blue-500/10' : 'bg-blue-50';
                            $borderColor = auth()->user()->role === 'admin' ? 'border-blue-500/20' : 'border-blue-100';
                            break;
                        case 'new_message':
                            $icon = 'message-square';
                            $iconColor = 'text-indigo-600';
                            $bgColor = auth()->user()->role === 'admin' ? 'bg-indigo-500/10' : 'bg-indigo-50';
                            $borderColor = auth()->user()->role === 'admin' ? 'border-indigo-500/20' : 'border-indigo-100';
                            break;
                        case 'application_sent':
                            $icon = 'send';
                            $iconColor = 'text-indigo-600';
                            $bgColor = auth()->user()->role === 'admin' ? 'bg-indigo-500/10' : 'bg-indigo-50';
                            $borderColor = auth()->user()->role === 'admin' ? 'border-indigo-500/20' : 'border-indigo-100';
                            break;
                        case 'application_received':
                            $icon = 'inbox';
                            $iconColor = 'text-purple-600';
                            $bgColor = auth()->user()->role === 'admin' ? 'bg-purple-500/10' : 'bg-purple-50';
                            $borderColor = auth()->user()->role === 'admin' ? 'border-purple-500/20' : 'border-purple-100';
                            break;
                        case 'application_accepted':
                            $icon = 'check-circle';
                            $iconColor = 'text-emerald-600';
                            $bgColor = auth()->user()->role === 'admin' ? 'bg-emerald-500/10' : 'bg-emerald-50';
                            $borderColor = auth()->user()->role === 'admin' ? 'border-emerald-500/20' : 'border-emerald-100';
                            break;
                        case 'application_refused':
                            $icon = 'x-circle';
                            $iconColor = 'text-rose-600';
                            $bgColor = auth()->user()->role === 'admin' ? 'bg-rose-500/10' : 'bg-rose-50';
                            $borderColor = auth()->user()->role === 'admin' ? 'border-rose-500/20' : 'border-rose-100';
                            break;
                        case 'interview':
                            $icon = 'calendar';
                            $iconColor = 'text-amber-600';
                            $bgColor = auth()->user()->role === 'admin' ? 'bg-amber-500/10' : 'bg-amber-50';
                            $borderColor = auth()->user()->role === 'admin' ? 'border-amber-500/20' : 'border-amber-100';
                            break;
                        default:
                            $icon = 'bell';
                            $iconColor = 'text-slate-600';
                            $bgColor = auth()->user()->role === 'admin' ? 'bg-slate-500/10' : 'bg-slate-50';
                            $borderColor = auth()->user()->role === 'admin' ? 'border-slate-500/20' : 'border-slate-150';
                    }
                    
                    // Style de fond global de la notification
                    if (auth()->user()->role === 'admin') {
                        $cardBg = $isUnread ? 'bg-slate-800 border-slate-700 hover:border-slate-600' : 'bg-slate-850/50 border-slate-800 opacity-75';
                    } else {
                        $cardBg = $isUnread ? 'bg-white border-slate-200 hover:border-indigo-200 shadow-sm' : 'bg-slate-100/60 border-slate-150 opacity-75';
                    }
                @endphp

                <!-- Formulaire cliquable pour marquer comme lu et rediriger -->
                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left block p-5 rounded-2xl border transition focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer {{ $cardBg }}">
                        <div class="flex items-start gap-4">
                            
                            <!-- Icône de Type -->
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center shrink-0 {{ $bgColor }} border {{ $borderColor }}">
                                <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $iconColor }}"></i>
                            </div>

                            <!-- Contenu Texte -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-1 mb-1">
                                    <h4 class="font-bold font-sora leading-tight {{ $isUnread ? (auth()->user()->role === 'admin' ? 'text-white' : 'text-slate-900') : 'text-slate-500' }}">
                                        {{ $data['title'] ?? 'Notification' }}
                                    </h4>
                                    <span class="text-xs text-slate-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-sm font-medium leading-relaxed {{ $isUnread ? (auth()->user()->role === 'admin' ? 'text-slate-300' : 'text-slate-600') : 'text-slate-400' }}">
                                    {{ $data['message'] ?? '' }}
                                </p>
                            </div>

                            <!-- Indicateur non lu -->
                            @if($isUnread)
                                <div class="h-2 w-2 rounded-full bg-rose-500 mt-2 shrink-0"></div>
                            @endif
                        </div>
                    </button>
                </form>
            @empty
                <!-- Message si vide -->
                <div class="p-12 text-center rounded-2xl border {{ auth()->user()->role === 'admin' ? 'bg-slate-850 border-slate-800' : 'bg-white border-slate-200 shadow-sm' }}">
                    <div class="h-16 w-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center mb-4">
                        <i data-lucide="bell-off" class="w-8 h-8 text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-bold font-sora mb-1">Aucune alerte pour le moment</h3>
                    <p class="text-sm text-slate-400">Vous recevrez des notifications ici dès qu'il y aura du nouveau sur votre profil ou vos offres.</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        </div>

    </main>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        lucide.createIcons();
    });
</script>
@endsection
