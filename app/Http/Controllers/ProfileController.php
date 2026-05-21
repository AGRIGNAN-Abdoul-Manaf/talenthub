<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        if (Auth::user()->is_banned) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte a été suspendu par un administrateur.'
            ]);
        }
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        if (Auth::user()->is_banned) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte a été suspendu par un administrateur.'
            ]);
        }
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], // Max 2Mo
            'password' => ['nullable', 'confirmed', 'min:8'],
            'phone' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'skills' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->bio = $request->bio;
        $user->skills = $request->skills;

        // Gestion de l'upload de l'avatar
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien fichier s'il existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Stocker le nouveau fichier dans storage/app/public/avatars
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // Modification optionnelle du mot de passe
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil mis à jour avec succès.');
    }
}