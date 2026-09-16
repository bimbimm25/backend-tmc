<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Event;
use App\Models\RobloxMission;
use Illuminate\Http\JsonResponse;

class PublicController extends Controller
{
    public function getHomeData(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'highlight_menus' => Menu::where('is_recommended', true)
                    ->orWhere('is_bestseller', true)
                    ->take(4)
                    ->get(),
                'latest_event' => Event::where('is_active', true)->latest()->first(),
                'active_mission' => RobloxMission::where('is_active', true)->latest()->first(),
            ]
        ]);
    }
}