<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use App\Models\JobListing;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'location',
        'website',
        'logo',
        'user_id'
    ];

    // Génération automatique du Slug avant la création en BDD
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($company) {
            $company->slug = Str::slug($company->name);
        });
    }

    // Liaison inverse : L'entreprise appartient à un utilisateur (Recruteur)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Image ou Logo par défaut dynamique
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo && file_exists(storage_path('app/public/' . $this->logo))) {
            return asset('storage/' . $this->logo);
        }
        
        // Si pas de logo, on génère un logo texte coloré avec l'initiale de l'entreprise
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0D8ABC&color=fff&size=128';
    }

    public function jobs(): HasMany
{
    return $this->hasMany(JobListing::class, 'company_id');
}
}