<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    // Afficher le formulaire de création
    public function create()
    {
        // SÉCURITÉ : Seuls les recruteurs et les admins peuvent créer une entreprise
        if (!auth()->user()->hasRole('recruteur') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Action non autorisée. Cet espace est réservé aux recruteurs et administrateurs.');
        }

        // Sécurité : Si l'utilisateur possède déjà une entreprise, on le redirige vers la modification
        if (auth()->user()->company) {
            return redirect()->route('company.edit')->with('info', 'Vous possédez déjà une entreprise.');
        }

        return view('company.create');
    }

    // Traiter l'enregistrement de l'entreprise
    public function store(Request $request)
    {
        // SÉCURITÉ : Seuls les recruteurs et les admins peuvent enregistrer une entreprise
        if (!auth()->user()->hasRole('recruteur') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Action non autorisée.');
        }

        // Validation stricte des données reçues
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name',
            'description' => 'nullable|string|max:2000',
            'location' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2Mo
        ]);

        $data = $request->only(['name', 'description', 'location', 'website']);
        $data['user_id'] = auth()->id(); // Liaison automatique avec le créateur

        // Gestion de l'upload du logo
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Company::create($data);

        return redirect()->route('recruteur.dashboard')->with('success', 'Votre entreprise a été enregistrée avec succès !');
    }

    // Afficher le formulaire de modification
    public function edit()
    {
        // SÉCURITÉ : Vérification de rôle pour la modification
        if (!auth()->user()->hasRole('recruteur') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Action non autorisée.');
        }

        $company = auth()->user()->company;

        // Si l'utilisateur n'a pas encore créé d'entreprise, on le redirige vers la création
        if (!$company) {
            return redirect()->route('company.create');
        }

        return view('company.edit', compact('company'));
    }

    // Traiter la mise à jour des données
    public function update(Request $request)
    {
        // SÉCURITÉ : Vérification de rôle pour la mise à jour
        if (!auth()->user()->hasRole('recruteur') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Action non autorisée.');
        }

        $company = auth()->user()->company;

        if (!$company) {
            abort(404, 'Entreprise introuvable.');
        }

        // Validation des données pour la mise à jour
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,' . $company->id,
            'description' => 'nullable|string|max:2000',
            'location' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'location', 'website']);

        // Si un nouveau logo est téléversé
        if ($request->hasFile('logo')) {
            // Suppression de l'ancien logo physique du serveur s'il existe
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            // Stockage du nouveau logo
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($data);

        return back()->with('success', 'Les informations de votre entreprise ont été mises à jour.');
    }

    // Supprimer l'entreprise
    public function destroy()
    {
        // SÉCURITÉ : Vérification de rôle pour la suppression
        if (!auth()->user()->hasRole('recruteur') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Action non autorisée.');
        }

        $company = auth()->user()->company;

        if (!$company) {
            abort(404);
        }

        // Suppression du fichier logo associé
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->delete();

        return redirect()->route('recruteur.dashboard')->with('success', 'L’entreprise a été supprimée définitivement.');
    }
}