<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;

    // 🔴 ON A SUPPRIMÉ LA LIGNE PROTECTED $PRIMARYKEY !
    protected $table = 'job_listings';

    protected $fillable = [
        'company_id',
        'title',
        'category',
        'description',
        'location',
        'contract_type',
        'education_level',
        'experience_required',
        'salary_range',
        'is_active',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }
}