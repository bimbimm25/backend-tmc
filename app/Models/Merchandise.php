<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Merchandise extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'name',
        'slug',
        'description',
        'price',
        'stock_status',
        'purchase_type',
        'image',
        'is_featured',
        'is_best_seller',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'price' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($merchandise) {
            if (empty($merchandise->slug)) {
                $merchandise->slug = Str::slug($merchandise->name);
            }
        });
    }
}