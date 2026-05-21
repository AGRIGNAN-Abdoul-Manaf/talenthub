<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';

    protected $fillable = [
        'user_id',
        'job_id',
        'cv_path',
        'cover_letter_path',
        'cover_letter',
        'status',
        'recruiter_notes',
        'interview_date',
        'interview_time',
        'interview_location',
        'interview_details',
        'messages',
    ];

    protected $casts = [
        'messages' => 'array',
        'interview_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
