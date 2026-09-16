<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RobloxClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'roblox_mission_id',
        'roblox_username',
        'whatsapp_number',
        'proof_image',
        'claim_code',
        'status',
        'admin_notes',
    ];

    public function mission(): BelongsTo
    {
        return $this->belongsTo(RobloxMission::class, 'roblox_mission_id');
    }
}