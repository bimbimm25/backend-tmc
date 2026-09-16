<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'career_id',
        'full_name',
        'email',
        'phone',
        'cover_letter',
        'resume_path',
    ];

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }
}