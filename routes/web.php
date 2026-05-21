<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\RecruteurController;
use App\Http\Controllers\NotificationController;

// ====================================================
// 🌍 ROUTES PUBLIQUES (Accessibles à tous)
// ====================================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/offres', [JobController::class, 'publicIndex'])->name('jobs.public_index');
Route::get('/offres/{job}', [JobController::class, 'show'])->name('jobs.show');

// ====================================================
// 🔐 AUTHENTIFICATION & RÉINITIALISATION (Invités uniquement)
// ====================================================
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login']);
    
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register']);

    // 💡 AJOUTS ESSENTIELS : Gestion du mot de passe oublié
    Route::get('/mot-de-passe-oublie', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reinitialiser-mot-de-passe/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reinitialiser-mot-de-passe', [AuthController::class, 'updatePassword'])->name('password.update');
});

Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ====================================================
// 🧑‍💻 PROFIL COMMUN (Candidat, Recruteur, Admin)
// ====================================================
Route::middleware('auth')->group(function () {
    Route::get('/profil', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // Alias pour la rétrocompatibilité si des vues y font référence
    Route::get('/candidat/profil', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('candidat.profile');
    Route::put('/candidat/profil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('candidat.profile.update');

    // 🔔 MODULE NOTIFICATIONS (In-App & Actions)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/lire', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/tout-lire', [NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');
});

// ====================================================
// 🛡️ ACCÈS FILTRÉS PAR RÔLES
// ====================================================

// 👤 ESPACE CANDIDAT
Route::middleware(['auth', RoleMiddleware::class . ':candidat'])->group(function () {
    Route::get('/candidat/dashboard', [CandidateController::class, 'dashboard'])->name('candidat.dashboard');
    
    Route::post('/offres/{job}/postuler', [CandidateController::class, 'apply'])->name('jobs.apply');
    Route::get('/mes-candidatures', [CandidateController::class, 'applications'])->name('candidat.applications');
    Route::delete('/candidat/candidatures/{application}/annuler', [CandidateController::class, 'cancelApplication'])->name('applications.cancel');
    
    // Favoris et Messagerie Candidat
    Route::post('/offres/{job}/favoris', [CandidateController::class, 'toggleFavorite'])->name('jobs.toggleFavorite');
    Route::post('/candidat/candidatures/{application}/repondre', [CandidateController::class, 'replyMessage'])->name('candidat.applications.replyMessage');
});

// 👑 ESPACE ADMIN
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/utilisateurs', [AdminController::class, 'users'])->name('admin.users');
    Route::patch('/admin/utilisateurs/{user}/toggle-ban', [AdminController::class, 'toggleBan'])->name('admin.users.ban');
    
    Route::get('/admin/entreprises', [AdminController::class, 'companies'])->name('admin.companies');
    Route::delete('/admin/entreprises/{company}', [AdminController::class, 'deleteCompany'])->name('admin.companies.delete');
    
    Route::get('/admin/offres', [AdminController::class, 'jobs'])->name('admin.jobs');
    Route::patch('/admin/offres/{job}/toggle', [AdminController::class, 'toggleJobStatus'])->name('admin.jobs.toggle');
    Route::delete('/admin/offres/{job}', [AdminController::class, 'deleteJob'])->name('admin.jobs.delete');
    
    Route::get('/admin/statistiques', [AdminController::class, 'stats'])->name('admin.stats');
});

// 📁 ESPACE RECRUTEUR & MODULES
Route::middleware(['auth', RoleMiddleware::class . ':recruteur,admin'])->group(function () {
    Route::get('/recruteur/dashboard', [RecruteurController::class, 'dashboard'])->name('recruteur.dashboard');
    Route::patch('/recruteur/applications/{application}/status', [RecruteurController::class, 'updateStatus'])->name('recruteur.applications.updateStatus');

    // Fiche Candidat RH & Outils Recruteur
    Route::get('/recruteur/candidatures/{application}/profil', [RecruteurController::class, 'candidateProfile'])->name('recruteur.candidate.profile');
    Route::post('/recruteur/candidatures/{application}/notes', [RecruteurController::class, 'saveNotes'])->name('recruteur.applications.saveNotes');
    Route::post('/recruteur/candidatures/{application}/entretien', [RecruteurController::class, 'scheduleInterview'])->name('recruteur.applications.scheduleInterview');
    Route::post('/recruteur/candidatures/{application}/contacter', [RecruteurController::class, 'sendMessage'])->name('recruteur.applications.sendMessage');

    Route::get('/company/create', [CompanyController::class, 'create'])->name('company.create');
    Route::post('/company', [CompanyController::class, 'store'])->name('company.store');
    Route::get('/company/edit', [CompanyController::class, 'edit'])->name('company.edit');
    Route::post('/company/update', [CompanyController::class, 'update'])->name('company.update');
    Route::delete('/company/delete', [CompanyController::class, 'destroy'])->name('company.destroy');

    Route::get('/mon-espace/offres', [JobController::class, 'index'])->name('jobs.index');
    Route::get('/mon-espace/offres/creation', [JobController::class, 'create'])->name('jobs.create');
    Route::post('/mon-espace/offres/stocker', [JobController::class, 'store'])->name('jobs.store');
    Route::get('/mon-espace/offres/{job}/modifier', [JobController::class, 'edit'])->name('jobs.edit');
    Route::put('/mon-espace/offres/{job}/mettre-a-jour', [JobController::class, 'update'])->name('jobs.update');
    Route::patch('/mon-espace/offres/{job}/toggle', [JobController::class, 'toggle'])->name('jobs.toggle');
    Route::delete('/mon-espace/offres/{job}/supprimer', [JobController::class, 'destroy'])->name('jobs.destroy');

    Route::get('/mon-espace/offres/{job}/candidats', [JobController::class, 'showApplications'])->name('recruteur.applications');
    Route::patch('/mon-espace/candidatures/{application}/statut', [JobController::class, 'updateApplicationStatus'])->name('recruteur.applications.status');
});