<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_key',
        'title',
        'subtitle',
        'image',
        'cta_text',
        'cta_link',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}