<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;

class JobListingController extends Controller
{
    // Afficher la liste des offres du recruteur connecté
    public function index()
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            return redirect()->route('company.create')->with('error', 'Vous devez créer une entreprise avant de publier une offre.');
        }

        // On récupère les annonces de CETTE entreprise
        $jobs = $company->jobs()->latest()->get();
        return view('recruteur.jobs.index', compact('jobs'));
    }

    // Formulaire de création
    public function create()
    {
        if (!auth()->user()->company) {
            return redirect()->route('company.create')->with('error', 'Créez d\'abord votre entreprise.');
        }
        return view('recruteur.jobs.create');
    }

    // Enregistrer l'offre d'emploi
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'contract_type' => 'required|string',
            'education_level' => 'required|string',
            'experience_required' => 'required|string',
            'salary_range' => 'nullable|string|max:255',
        ]);

        // Liaison automatique avec l'entreprise du recruteur connecté
        auth()->user()->company->jobs()->create($request->all());

        return redirect()->route('jobs.index')->with('success', 'L\'offre d\'emploi a été publiée avec succès !');
    }

    // Formulaire de modification
    public function edit(JobListing $job)
    {
        // Sécurité : On vérifie que cette offre appartient bien à l'entreprise du recruteur
        if ($job->company_id !== auth()->user()->company->id) {
            abort(403);
        }

        return view('recruteur.jobs.edit', compact('job'));
    }

    // Mettre à jour l'offre
    public function update(Request $request, JobListing $job)
    {
        if ($job->company_id !== auth()->user()->company->id) { abort(403); }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'contract_type' => 'required|string',
            'education_level' => 'required|string',
            'experience_required' => 'required|string',
            'salary_range' => 'nullable|string|max:255',
        ]);

        $job->update($request->all());

        return redirect()->route('jobs.index')->with('success', 'Offre mise à jour avec succès.');
    }

    // Activer / Désactiver une offre (Bouton switch)
    public function toggleStatus(JobListing $job)
    {
        if ($job->company_id !== auth()->user()->company->id) { abort(403); }

        $job->update(['is_active' => !$job->is_active]);

        return back()->with('success', $job->is_active ? 'Offre activée et visible.' : 'Offre désactivée.');
    }

    // Supprimer l'offre
    public function destroy(JobListing $job)
    {
        if ($job->company_id !== auth()->user()->company->id) { abort(403); }

        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'L\'offre d\'emploi a été supprimée.');
    }
}