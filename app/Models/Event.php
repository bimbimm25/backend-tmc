<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'category',
        'slug',
        'description',
        'price',
        'capacity',
        'event_date',
        'location_name',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'float',
        'capacity' => 'integer',
        'event_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }
}