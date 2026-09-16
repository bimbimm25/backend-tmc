<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'name',
        'description',
        'price',
        'purchase_option',
        'location',
        'image',
        'is_bestseller',
        'is_recommended',
    ];

    protected $casts = [
        'is_bestseller' => 'boolean',
        'is_recommended' => 'boolean',
        'price' => 'float',
    ];
}