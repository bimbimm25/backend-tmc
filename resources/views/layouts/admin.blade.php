<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f9f6f0]">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - To Meet Cafe')</title>

    <!-- Tailwind CSS Play CDN (Mencegah Tampilan Rusak Permanen) -->
    <script src="https://cdn.tailwindcss.com"></script>

    @livewireStyles
</head>

<body class="h-full bg-[#f9f6f0] text-stone-800 font-sans antialiased">

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col lg:flex-row">

        <!-- Overlay Sidebar Mobile -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-stone-900/40 backdrop-blur-sm lg:hidden" x-cloak></div>

        <!-- SIDEBAR UTAMA (TERKUNCI DI KIRI) -->
        <div class="lg:sticky lg:top-0 lg:h-screen lg:shrink-0 z-50">
            @include('layouts.partials.sidebar')
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen">

            <!-- TOPBAR HEADER -->
            <header
                class="bg-white/90 backdrop-blur-md border-b border-stone-200/80 sticky top-0 z-30 px-4 sm:px-6 py-3 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 text-stone-600 hover:text-stone-900 rounded-xl hover:bg-stone-100 lg:hidden focus:outline-none shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="truncate">
                        <h1 class="text-sm sm:text-base font-bold text-stone-900 leading-tight truncate">
                            @yield('page_title', 'Dashboard')
                        </h1>
                        <p class="text-[10px] text-stone-500 hidden sm:block">Panel manajemen resmi To Meet Cafe</p>
                    </div>
                </div>

                <!-- Profile Info & Avatar -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs font-bold text-stone-900">{{ auth()->user()->name ?? 'Administrator' }}
                        </div>
                        <div class="text-[10px] text-stone-500">{{ auth()->user()->email ?? 'admin@tomeetcafe.com' }}
                        </div>
                    </div>
                    <div
                        class="w-8 h-8 rounded-xl bg-[#8c5a3c] text-white flex items-center justify-center font-bold text-xs shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
            </header>

            <!-- CONTENT BODY AREA -->
            <main class="flex-1 p-4 sm:p-6">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>

    </div>

    @livewireScripts
</body>

</html>