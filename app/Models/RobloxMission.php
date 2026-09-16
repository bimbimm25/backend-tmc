<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RobloxMission extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'title',
        'badge_name',
        'requirement',
        'reward_title',
        'reward_code',
        'roblox_map_link',
        'trailer_video_url',
        'description',
        'image',
        'is_active',
    ];
}