<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Déclencher la notification de bienvenue (Email + Alerte In-App)
        $user->notify(new \App\Notifications\UserRegisteredNotification($user));

        Auth::login($user);

        return redirect()->to($this->getRedirectPath($user))
            ->with('success', 'Votre compte a bien été créé !');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            if (auth()->user()->is_banned) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Votre compte a été suspendu par un administrateur.',
                ])->onlyInput('email');
            }
            $request->session()->regenerate();
            return redirect()->intended($this->getRedirectPath(auth()->user()));
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // 💡 AJOUT : Affiche la page "Demande de réinitialisation" (Entrez votre email)
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // 💡 AJOUT : Gère l'envoi fictif ou réel du token de réinitialisation
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(60);

        // On insère le jeton en base dans la table standard de Laravel
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Pour te faciliter le test en local sans serveur mail configuré :
        $lienFictif = route('password.reset', ['token' => $token, 'email' => $request->email]);

        return back()->with('status', 'Lien de réinitialisation généré ! Pour le test, voici le lien direct : ' . $lienFictif);
    }

    // 💡 AJOUT : Affiche ton formulaire de configuration du NOUVEAU mot de passe
    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // 💡 AJOUT : Valide et met à jour le mot de passe en base de données
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Ce jeton de réinitialisation est invalide.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Votre mot de passe a été mis à jour avec succès !');
    }

    private function getRedirectPath($user)
    {
        if ($user->role === 'admin') {
            return route('admin.dashboard');
        } elseif ($user->role === 'recruteur') {
            return route('recruteur.dashboard');
        }
        
        return route('candidat.dashboard');
    }
}