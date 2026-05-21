@extends('layouts.app', ['title' => 'Discussion avec ' . $interlocuteur->name])

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <!-- En-tête de la discussion -->
    <div class="bg-white border border-gray-200 rounded-t-xl p-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                {{ strtoupper(substr($interlocuteur->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="font-bold text-gray-800">{{ $interlocuteur->name }}</h1>
                <span class="text-xs font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-600 capitalize">
                    Role : {{ $interlocuteur->role }}
                </span>
            </div>
        </div>
        <a href="{{ route('messages.index') }}" class="text-sm text-gray-500 hover:text-blue-600 font-medium">← Retour</a>
    </div>

    <!-- Boîte de dialogue de l'historique -->
    <div id="chat-box" class="bg-gray-50 border-x border-gray-200 h-[450px] overflow-y-auto p-4 space-y-4 flex flex-col">
        @if($messages->isEmpty())
            <p class="text-center text-gray-400 my-auto">Aucun message échangé. Envoyez le premier message !</p>
        @else
            @foreach($messages as $msg)
                @if($msg->sender_id === auth()->id())
                    <!-- Message envoyé par l'utilisateur connecté (Aligné à droite - Bleu) -->
                    <div class="self-end max-w-md bg-blue-600 text-white rounded-2xl rounded-tr-none px-4 py-2 shadow-sm">
                        <p class="text-sm leading-relaxed">{{ $msg->content }}</p>
                        <span class="block text-[10px] text-blue-100 text-right mt-1">{{ $msg->created_at->format('H:i') }}</span>
                    </div>
                @else
                    <!-- Message reçu (Aligné à gauche - Blanc/Gris) -->
                    <div class="self-start max-w-md bg-white text-gray-800 border border-gray-200 rounded-2xl rounded-tl-none px-4 py-2 shadow-sm">
                        <p class="text-sm leading-relaxed">{{ $msg->content }}</p>
                        <span class="block text-[10px] text-gray-400 mt-1">{{ $msg->created_at->format('H:i') }}</span>
                    </div>
                @endif
            @endforeach
        @endif
    </div>

    <!-- Zone d'envoi de message -->
    <div class="bg-white border border-gray-200 rounded-b-xl p-4 shadow-sm">
        <form action="{{ route('messages.store', $interlocuteur) }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="content" placeholder="Écrivez votre message ici..." class="flex-1 border rounded-lg px-4 py-2 focus:outline-blue-500 text-sm" required autocomplete="off">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2 rounded-lg text-sm transition">
                Envoyer
            </button>
        </form>
    </div>
</div>

<script>
    // Force le défilement vers le bas du chat dès le chargement
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endsection