<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed lg:static inset-y-0 left-0 z-50 w-64 h-full bg-white border-r border-stone-200/80 flex flex-col justify-between transition-transform duration-300 ease-in-out lg:translate-x-0 shrink-0 shadow-sm overflow-y-auto">
    <div>
        <!-- Brand Logo Header -->
        <div class="p-4 sm:p-5 border-b border-stone-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div
                    class="w-9 h-9 rounded-xl bg-[#fffcf7] border border-[#e6ccb2] p-1 flex items-center justify-center shadow-2xs">
                    <img src="/img/logo-tomeet.png" alt="To Meet Cafe Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <span
                        class="font-black text-stone-900 text-xs sm:text-sm tracking-wider uppercase block leading-tight">TO
                        MEET CAFE</span>
                    <span class="text-[9px] font-bold text-[#8c5a3c] uppercase tracking-widest block mt-0.5">Admin
                        Panel</span>
                </div>
            </div>

            <!-- Close Mobile Drawer Button -->
            <button @click="sidebarOpen = false"
                class="p-1.5 text-stone-400 hover:text-stone-700 rounded-lg lg:hidden cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="p-3 space-y-1">
            <!-- 1. Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-[#8c5a3c] text-white shadow-sm shadow-[#8c5a3c]/20' : 'text-stone-600 hover:bg-stone-100/80 hover:text-stone-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 13.2V6a1 1 0 011-1h6a1 1 0 011 1v7.2M3 13.2v4.8a1 1 0 001 1h6a1 1 0 001-1v-4.8M3 13.2h8M13 18v-7.2a1 1 0 011-1h6a1 1 0 011 1V18a1 1 0 01-1 1h-6a1 1 0 01-1-1zM13 10.8h8" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- 2. Kelola Banner -->
            <a href="{{ route('admin.banners') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.banners*') ? 'bg-[#8c5a3c] text-white shadow-sm shadow-[#8c5a3c]/20' : 'text-stone-600 hover:bg-stone-100/80 hover:text-stone-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Kelola Banner</span>
            </a>

            <!-- 3. Kelola Menu -->
            <a href="{{ route('admin.menus.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.menus.*') ? 'bg-[#8c5a3c] text-white shadow-sm shadow-[#8c5a3c]/20' : 'text-stone-600 hover:bg-stone-100/80 hover:text-stone-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 2v6a4 4 0 01-4 4H4a2 2 0 01-2-2V2h14zm0 0v20M8 2v10M12 2v10" />
                </svg>
                <span>Kelola Menu</span>
            </a>

            <!-- 4. Kelola Merchandise -->
            <a href="{{ route('admin.merchandise.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.merchandise.*') ? 'bg-[#8c5a3c] text-white shadow-sm shadow-[#8c5a3c]/20' : 'text-stone-600 hover:bg-stone-100/80 hover:text-stone-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>Kelola Merchandise</span>
            </a>

            <!-- 5. Event & Workshop -->
            <a href="{{ route('admin.events.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.events.*') ? 'bg-[#8c5a3c] text-white shadow-sm shadow-[#8c5a3c]/20' : 'text-stone-600 hover:bg-stone-100/80 hover:text-stone-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Event & Workshop</span>
            </a>

            <a href="{{ route('admin.birthday.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.birthday.*') ? 'bg-[#8c5a3c] text-white shadow-sm shadow-[#8c5a3c]/20' : 'text-stone-600 hover:bg-stone-100/80 hover:text-stone-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v13m0-13V4a2 2 0 112 2h-2zm0 0V4a2 2 0 10-2 2h2m-7 8h14a2 2 0 012 2v5H3v-5a2 2 0 012-2z" />
                </svg>
                <span>Birthday</span>
            </a>



            <!-- 7. Roblox Misi & Reward -->
            <a href="{{ route('admin.roblox.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.roblox.*') ? 'bg-[#8c5a3c] text-white shadow-sm shadow-[#8c5a3c]/20' : 'text-stone-600 hover:bg-stone-100/80 hover:text-stone-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 5T6 7a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2H9zM6 12h4m-2-2v4m10 0h.01M16 10h.01" />
                </svg>
                <span>Roblox Misi & Reward</span>
            </a>

            <!-- 8. Kelola Karir -->
            <a href="{{ route('admin.careers.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.careers.*') ? 'bg-[#8c5a3c] text-white shadow-sm shadow-[#8c5a3c]/20' : 'text-stone-600 hover:bg-stone-100/80 hover:text-stone-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>Kelola Karir</span>
            </a>

            <!-- 9. Berita & Blog -->
            <a href="{{ route('admin.posts.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.posts.*') ? 'bg-[#8c5a3c] text-white shadow-sm shadow-[#8c5a3c]/20' : 'text-stone-600 hover:bg-stone-100/80 hover:text-stone-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6m-6 4h6" />
                </svg>
                <span>Berita & Blog</span>
            </a>
        </nav>
    </div>

    <!-- Bottom Footer Logout Button -->
    <div class="p-3 border-t border-stone-100 bg-white">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar Sesi</span>
                </div>
            </button>
        </form>
    </div>
</aside>