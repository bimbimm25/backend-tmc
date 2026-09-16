<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Models\Menu;
use App\Models\Merchandise;
use App\Models\Event;
use App\Models\RobloxMission;
use App\Models\Career;
use App\Models\JobApplication;
use App\Models\Post;
use App\Models\BlogCategory;
use App\Mail\NewApplicantNotification;
use App\Models\Banner;
use App\Http\Controllers\Api\RobloxApiController;

/*
|--------------------------------------------------------------------------
| API Routes - Brand Backend (Consumed by Next.js Frontend)
|--------------------------------------------------------------------------
*/

// ==========================================
// 0. MODUL HOMEPAGE
// ==========================================
Route::get('/home-data', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Berhasil mengambil data homepage',
        'data' => [
            'highlight_menus' => Menu::where('is_recommended', true)
                ->orWhere('is_bestseller', true)
                ->latest()
                ->take(4)
                ->get(),

            'latest_event' => Event::where('is_active', true)
                ->latest()
                ->first(),

            'active_mission' => RobloxMission::where('is_active', true)
                ->latest()
                ->first(),
        ]
    ], 200);
});


// ==========================================
// 1. MODUL DIGITAL MENU
// ==========================================
Route::get('/menus', function (Request $request) {
    $category = $request->query('category');

    $menus = Menu::when($category, fn($q) => $q->where('category', $category))
        ->latest()
        ->get();

    return response()->json([
        'status' => 'success',
        'message' => 'Berhasil mengambil data menu',
        'data' => $menus
    ], 200);
});


// ==========================================
// 2. MODUL MERCHANDISE
// ==========================================
Route::get('/merchandise', function (Request $request) {
    $status = $request->query('status');

    $merchandise = Merchandise::when($status, fn($q) => $q->where('stock_status', $status))
        ->latest()
        ->get();

    return response()->json([
        'status' => 'success',
        'message' => 'Berhasil mengambil katalog merchandise',
        'data' => $merchandise
    ], 200);
});

Route::get('/merchandise/{slug}', function ($slug) {
    $item = Merchandise::where('slug', $slug)->firstOrFail();

    return response()->json([
        'status' => 'success',
        'message' => 'Berhasil mengambil detail merchandise',
        'data' => $item
    ], 200);
});


// ==========================================
// 3. MODUL EVENT, WORKSHOP & BIRTHDAY
// ==========================================
Route::get('/events', function (Request $request) {
    $query = Event::where('is_active', true);

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    $events = $query->latest()->get();

    return response()->json([
        'status' => 'success',
        'message' => 'Berhasil mengambil daftar event',
        'data' => $events
    ], 200);
});

Route::get('/birthdays', function () {
    $birthdays = Event::where('type', 'birthday_package')
        ->where('is_active', true)
        ->latest()
        ->get();

    $banner = Banner::where('page_key', 'birthday')
        ->where('is_active', true)
        ->first();

    return response()->json([
        'status' => 'success',
        'message' => 'Berhasil mengambil daftar paket birthday',
        'data' => [
            'banner' => $banner,
            'packages' => $birthdays,
        ]
    ], 200);
});


// ==========================================
// 4. MODUL ROBLOX & CLAIMS
// ==========================================
Route::get('/roblox-data', [RobloxApiController::class, 'index']);
Route::post('/roblox/claim', [RobloxApiController::class, 'submitClaim']);


// ==========================================
// 5. MODUL KARIR & FORM PELAMAR (EMAIL)
// ==========================================
Route::get('/careers', function () {
    $careers = Career::where('is_active', true)
        ->latest()
        ->get();

    return response()->json([
        'status' => 'success',
        'message' => 'Berhasil mengambil lowongan karir aktif',
        'data' => $careers
    ], 200);
});

Route::post('/careers/apply', function (Request $request) {
    $validated = $request->validate([
        'career_id' => 'required|exists:careers,id',
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'cover_letter' => 'nullable|string',
        'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
    ]);

    if ($request->hasFile('resume')) {
        $validated['resume_path'] = $request->file('resume')->store('resumes', 'public');
    }

    $application = JobApplication::create($validated);

    $ownerEmail = env('OWNER_EMAIL', 'owner@tomeetcafe.com');
    try {
        Mail::to($ownerEmail)->send(new NewApplicantNotification($application));
    } catch (\Exception $e) {
        
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Lamaran Anda berhasil dikirim!',
        'data' => $application
    ], 201);
});


// ==========================================
// 6. MODUL BLOG, CATEGORIES & SEO STORIES
// ==========================================

// Endpoint lengkap data Blog (Posts, Categories, Featured, Popular, Banner)
Route::get('/blog-data', function (Request $request) {
    $categorySlug = $request->query('category');
    $search = $request->query('search');

    $postsQuery = Post::with('category')
        ->where('is_published', true)
        ->latest();

    if ($categorySlug && $categorySlug !== 'all') {
        $postsQuery->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    if ($search) {
        $postsQuery->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%")
                ->orWhere('meta_description', 'like', "%{$search}%");
        });
    }

    $posts = $postsQuery->get();

    $categories = BlogCategory::withCount([
        'posts' => function ($q) {
            $q->where('is_published', true);
        }
    ])->get();

    $featured = Post::with('category')
        ->where('is_published', true)
        ->latest('views')
        ->first() ?? $posts->first();

    $popular = Post::with('category')
        ->where('is_published', true)
        ->orderByDesc('views')
        ->take(3)
        ->get();

    $banner = Banner::where('page_key', 'blog')
        ->where('is_active', true)
        ->first();

    return response()->json([
        'status' => 'success',
        'message' => 'Berhasil mengambil data blog',
        'data' => [
            'banner' => $banner,
            'featured' => $featured,
            'posts' => $posts,
            'categories' => $categories,
            'popular' => $popular,
        ]
    ], 200);
});

// Endpoint Detail Blog Single Post
Route::get('/blog/{slug}', function ($slug) {
    $post = Post::with('category')
        ->where('slug', $slug)
        ->where('is_published', true)
        ->firstOrFail();

    $post->increment('views');

    $related = Post::with('category')
        ->where('id', '!=', $post->id)
        ->where('is_published', true)
        ->latest()
        ->take(3)
        ->get();

    return response()->json([
        'status' => 'success',
        'message' => 'Berhasil mengambil detail artikel',
        'data' => [
            'post' => $post,
            'related' => $related,
        ]
    ], 200);
});

// Fallback Endpoint Posts Standar
Route::get('/posts', function (Request $request) {
    $posts = Post::with('category')
        ->where('is_published', true)
        ->latest()
        ->get();

    return response()->json([
        'status' => 'success',
        'message' => 'Berhasil mengambil daftar postingan',
        'data' => $posts
    ], 200);
});


// ==========================================
// 7. MODUL BANNERS
// ==========================================

Route::get('/banners/{pageKey}', function ($pageKey) {
    $banner = Banner::where('page_key', $pageKey)
        ->where('is_active', true)
        ->first();

    return response()->json([
        'status' => 'success',
        'data' => $banner
    ], 200);
});

Route::get('/banners', function () {
    $banners = Banner::where('is_active', true)->get()->keyBy('page_key');

    return response()->json([
        'status' => 'success',
        'data' => $banners
    ], 200);
});