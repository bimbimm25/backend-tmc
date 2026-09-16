<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Career extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'department',
        'type',
        'location_name',
        'description',
        'requirements',
        'benefits',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($career) {
            if (empty($career->slug)) {
                $career->slug = Str::slug($career->title);
            }
        });
    }
}