<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobListing extends Model
{
    // On indique explicitement le nom de la table pour éviter toute confusion
    protected $table = 'job_listings';

    protected $fillable = [
        'company_id',
        'title',
        'category',
        'description',
        'salary_range',
        'location',
        'contract_type',
        'education_level',
        'experience_required',
        'is_active'
    ];

    /**
     * Une offre d'emploi appartient à une entreprise
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Une offre d'emploi possède plusieurs candidatures
     */
    public function applications(): HasMany
    {
        // Forçage de 'job_id' pour correspondre à la structure de ta table SQL
        return $this->hasMany(Application::class, 'job_id');
    }
}