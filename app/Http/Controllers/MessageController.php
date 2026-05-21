<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    // 1. Afficher la liste de toutes les personnes avec qui on a discuté
    public function index()
    {
        $authId = Auth::id();

        // Requête SQL optimisée pour récupérer le dernier contact de chaque discussion
        $contactIds = Message::where('sender_id', $authId)
            ->orWhere('receiver_id', $authId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($message) use ($authId) {
                return $message->sender_id == $authId ? $message->receiver_id : $message->sender_id;
            })
            ->unique();

        $conversations = User::whereIn('id', $contactIds)->get();

        return view('messages.index', compact('conversations'));
    }

    // 2. Afficher la discussion avec un utilisateur précis + Marquer comme lu
    public function show(User $user)
    {
        $authId = Auth::id();

        // Récupérer l'historique complet entre les deux interlocuteurs
        $messages = Message::where(function($query) use ($authId, $user) {
            $query->where('sender_id', $authId)->where('receiver_id', $user->id);
        })->orWhere(function($query) use ($authId, $user) {
            $query->where('sender_id', $user->id)->where('receiver_id', $authId);
        })->orderBy('created_at', 'asc')->get();

        // En entrant dans la discussion, on marque tous les messages reçus de cet utilisateur comme "lus"
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.show', [
            'interlocuteur' => $user,
            'messages' => $messages
        ]);
    }

    // 3. Traiter et sauvegarder l'envoi d'un message
    public function store(Request $request, User $user)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Message envoyé.');
    }
}