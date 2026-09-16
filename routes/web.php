<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Auth\Login;
use App\Livewire\Admin\MenuManager;
use App\Livewire\Admin\MerchandiseManager;
use App\Livewire\Admin\EventManager;
use App\Livewire\Admin\RobloxManager;
use App\Livewire\Admin\CareerManager;
use App\Livewire\Admin\PostManager;
use App\Livewire\Admin\BannerManager;
use App\Livewire\Admin\BirthdayManager;

// Import Model untuk Statistik Dashboard Real-time
use App\Models\Menu;
use App\Models\Merchandise;
use App\Models\Event;
use App\Models\RobloxMission;
use App\Models\JobApplication;
use App\Models\Post;

// 1. Root: Jika belum login langsung arahkan ke /login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Halaman Login (Bisa diakses untuk login admin)
Route::get('/login', Login::class)->name('login');

// 3. Action Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

// 4. WAJIB LOGIN (Semua Halaman Dashboard Admin)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('admin.dashboard', [
            'totalMenu' => Menu::count(),
            'totalMerchandise' => Merchandise::count(),
            'totalEvent' => Event::where('is_active', true)->count(),
            'totalRoblox' => RobloxMission::count(),
            'totalApplications' => JobApplication::count(),
            'totalPosts' => Post::count(),
            'recentApplicants' => JobApplication::with('career')->latest()->take(4)->get(),
        ]);
    })->name('dashboard');

    Route::get('/menus', MenuManager::class)->name('menus.index');
    Route::get('/merchandise', MerchandiseManager::class)->name('merchandise.index');
    Route::get('/birthday', BirthdayManager::class)->name('birthday.index');
    Route::get('/events', EventManager::class)->name('events.index');
    Route::get('/roblox', RobloxManager::class)->name('roblox.index');
    Route::get('/careers', CareerManager::class)->name('careers.index');
    Route::get('/posts', PostManager::class)->name('posts.index');
    Route::get('/banners', BannerManager::class)->name('banners');
});