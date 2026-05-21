<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Liste toutes les notifications de l'utilisateur connecté
     */
    public function index()
    {
        $user = auth()->user();
        
        // Récupérer toutes les notifications (lues et non lues) paginées
        $notifications = $user->notifications()->paginate(10);
        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Marquer une notification spécifique comme lue et rediriger vers son lien d'action
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        $notification->markAsRead();

        // Si l'alerte dispose d'un lien d'action cible dans ses métadonnées, on redirige l'utilisateur dessus
        $actionUrl = $notification->data['action_url'] ?? null;

        if ($actionUrl) {
            return redirect($actionUrl);
        }

        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marquer toutes les notifications non lues comme lues
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Toutes vos notifications ont été marquées comme lues.');
    }
}
