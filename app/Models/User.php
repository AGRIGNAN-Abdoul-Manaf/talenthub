<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Ajouté
        'avatar', // Ajouté
        'phone', // Ajouté
        'bio', // Ajouté
        'skills', // Ajouté
        'is_banned', // Ajouté
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_banned' => 'boolean',
    ];

    // Helper pour vérifier les rôles facilement
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    // Accesseur pour récupérer l'URL de l'avatar ou une image par défaut
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar 
            ? Storage::url($this->avatar) 
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
    }

    // Un utilisateur (Recruteur) peut posséder une entreprise
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    // Un utilisateur (Candidat) peut soumettre plusieurs candidatures
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // Un utilisateur (Candidat) peut mettre plusieurs offres en favoris
    public function favoriteJobs()
    {
        return $this->belongsToMany(JobListing::class, 'favorites', 'user_id', 'job_id')->withTimestamps();
    }

    
}