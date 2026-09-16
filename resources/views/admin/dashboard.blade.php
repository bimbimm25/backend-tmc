@extends('layouts.admin')

@section('title', 'Dashboard Utama - To Meet Cafe')
@section('page_title', 'Ringkasan Sistem')

@section('content')
    <div class="space-y-2 max-w-7xl mx-auto h-full flex flex-col justify-between pb-2">

        <!-- BANNER RINGKAS -->
        <div
            class="bg-[#8c5a3c] border border-[#73482f] px-4 py-2.5 rounded-2xl shadow-md text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 shrink-0">
            <div class="space-y-0.5">
                <div
                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/20 text-[9.5px] font-bold text-amber-100 border border-white/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Panel Admin Aktif
                </div>
                <h2 class="text-sm sm:text-base font-black tracking-tight text-white leading-tight">Selamat Datang Kembali!
                </h2>
                <p class="text-[11px] text-amber-100/90 font-medium leading-none">
                    Ringkasan data produk, pendaftaran pelamar, dan modul To Meet Cafe hari ini.
                </p>
            </div>

            <a href="{{ route('admin.menus.index') }}"
                class="px-3 py-1.5 bg-white text-[#8c5a3c] hover:bg-amber-50 active:scale-95 text-xs font-black rounded-xl shadow-md transition flex items-center gap-1.5 shrink-0 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Menu</span>
            </a>
        </div>

        <!-- STAT CARDS GRID (6 KARTU ULTRA COMPACT) -->
        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-2 shrink-0">

            <!-- Total Menu -->
            <div
                class="bg-white px-3 py-2.5 rounded-xl shadow-xs border border-stone-200/80 flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-black text-stone-400 uppercase tracking-wider">Total Menu</p>
                    <h3 class="text-sm font-black text-stone-900 mt-0.5">
                        {{ $totalMenu ?? 0 }}
                    </h3>
                </div>
                <div
                    class="w-7 h-7 rounded-lg bg-amber-50 text-[#8c5a3c] flex items-center justify-center border border-amber-100 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 2v6a4 4 0 01-4 4H4a2 2 0 01-2-2V2h14zm0 0v20M8 2v10M12 2v10" />
                    </svg>
                </div>
            </div>

            <!-- Merchandise -->
            <div
                class="bg-white px-3 py-2.5 rounded-xl shadow-xs border border-stone-200/80 flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-black text-stone-400 uppercase tracking-wider">Merchandise</p>
                    <h3 class="text-sm font-black text-stone-900 mt-0.5">
                        {{ $totalMerchandise ?? 0 }}
                    </h3>
                </div>
                <div
                    class="w-7 h-7 rounded-lg bg-amber-50 text-[#8c5a3c] flex items-center justify-center border border-amber-100 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>

            <!-- Event & Birthday -->
            <div
                class="bg-white px-3 py-2.5 rounded-xl shadow-xs border border-stone-200/80 flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-black text-stone-400 uppercase tracking-wider">Event Aktif</p>
                    <h3 class="text-sm font-black text-stone-900 mt-0.5">
                        {{ $totalEvent ?? 0 }}
                    </h3>
                </div>
                <div
                    class="w-7 h-7 rounded-lg bg-amber-50 text-[#8c5a3c] flex items-center justify-center border border-amber-100 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <!-- Misi Roblox -->
            <div
                class="bg-white px-3 py-2.5 rounded-xl shadow-xs border border-stone-200/80 flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-black text-stone-400 uppercase tracking-wider">Misi Roblox</p>
                    <h3 class="text-sm font-black text-stone-900 mt-0.5">
                        {{ $totalRoblox ?? 0 }}
                    </h3>
                </div>
                <div
                    class="w-7 h-7 rounded-lg bg-amber-50 text-[#8c5a3c] flex items-center justify-center border border-amber-100 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 5T6 7a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2H9zM6 12h4m-2-2v4m10 0h.01M16 10h.01" />
                    </svg>
                </div>
            </div>

            <!-- Pelamar Karir -->
            <div
                class="bg-white px-3 py-2.5 rounded-xl shadow-xs border border-stone-200/80 flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-black text-stone-400 uppercase tracking-wider">Pelamar Kerja</p>
                    <h3 class="text-sm font-black text-stone-900 mt-0.5">
                        {{ $totalApplications ?? 0 }}
                    </h3>
                </div>
                <div
                    class="w-7 h-7 rounded-lg bg-amber-50 text-[#8c5a3c] flex items-center justify-center border border-amber-100 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>

            <!-- Artikel Blog -->
            <div
                class="bg-white px-3 py-2.5 rounded-xl shadow-xs border border-stone-200/80 flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-black text-stone-400 uppercase tracking-wider">Artikel Blog</p>
                    <h3 class="text-sm font-black text-stone-900 mt-0.5">
                        {{ $totalPosts ?? 0 }}
                    </h3>
                </div>
                <div
                    class="w-7 h-7 rounded-lg bg-amber-50 text-[#8c5a3c] flex items-center justify-center border border-amber-100 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6m-6 4h6" />
                    </svg>
                </div>
            </div>

        </div>

        <!-- BOTTOM GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-2.5 shrink-0">

            <!-- PELAMAR KERJA TERBARU -->
            <div
                class="lg:col-span-7 bg-white p-3.5 rounded-2xl shadow-xs border border-stone-200/80 flex flex-col justify-between space-y-2">
                <div>
                    <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                        <div>
                            <h3 class="text-xs font-black text-stone-900 leading-none">Pelamar Kerja Terbaru</h3>
                            <p class="text-[9.5px] font-medium text-stone-400 mt-0.5">Berkas aplikasi yang baru masuk lewat
                                website</p>
                        </div>
                        <a href="{{ route('admin.careers.index') }}"
                            class="text-[10px] font-black text-[#8c5a3c] hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    <div class="overflow-x-auto pt-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-stone-50/80 text-[9px] font-black text-stone-400 uppercase tracking-wider">
                                    <th class="py-1.5 px-2">Nama Pelamar</th>
                                    <th class="py-1.5 px-2">Posisi Dilamar</th>
                                    <th class="py-1.5 px-2">Kontak Email</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 text-[11px]">
                                @forelse($recentApplicants ?? [] as $app)
                                    <tr class="hover:bg-stone-50/50 transition">
                                        <td class="py-2 px-2 font-bold text-stone-900 truncate max-w-[120px]">
                                            {{ data_get($app, 'full_name', '-') }}</td>
                                        <td class="py-2 px-2 text-[#8c5a3c] font-bold truncate max-w-[140px]">
                                            {{ data_get($app, 'career.title', 'Posisi Dihapus') }}
                                        </td>
                                        <td class="py-2 px-2 text-stone-600 font-medium truncate max-w-[140px]">
                                            {{ data_get($app, 'email', '-') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-6 text-center text-stone-400 text-xs font-medium">Belum ada
                                            data pelamar kerja baru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="text-[9.5px] text-stone-400 text-center pt-1.5 border-t border-stone-100 font-medium">
                    To Meet Cafe System Status: <span class="text-emerald-600 font-bold">Online</span>
                </div>
            </div>

            <!-- AKSES CEPAT MODUL -->
            <div class="lg:col-span-5 bg-white p-3.5 rounded-2xl shadow-xs border border-stone-200/80 space-y-2">
                <h3 class="text-xs font-black text-stone-900 pb-1.5 border-b border-stone-100">Akses Cepat Modul</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-1">
                    <!-- 1. Kelola Menu -->
                    <a href="{{ route('admin.menus.index') }}"
                        class="px-2.5 py-1.5 rounded-xl border border-stone-100 hover:border-amber-200 hover:bg-amber-50/40 transition flex items-center justify-between group">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-5 h-5 rounded-lg bg-amber-100/70 text-[#8c5a3c] flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 2v6a4 4 0 01-4 4H4a2 2 0 01-2-2V2h14zm0 0v20M8 2v10M12 2v10" />
                                </svg>
                            </div>
                            <span class="text-[11px] font-bold text-stone-700 group-hover:text-[#8c5a3c]">Kelola Menu</span>
                        </div>
                        <svg class="w-3 h-3 text-stone-300 group-hover:text-[#8c5a3c] transition shrink-0" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- 2. Merchandise -->
                    <a href="{{ route('admin.merchandise.index') }}"
                        class="px-2.5 py-1.5 rounded-xl border border-stone-100 hover:border-amber-200 hover:bg-amber-50/40 transition flex items-center justify-between group">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-5 h-5 rounded-lg bg-amber-100/70 text-[#8c5a3c] flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <span class="text-[11px] font-bold text-stone-700 group-hover:text-[#8c5a3c]">Kelola
                                Merchandise</span>
                        </div>
                        <svg class="w-3 h-3 text-stone-300 group-hover:text-[#8c5a3c] transition shrink-0" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- 3. Event & Birthday -->
                    <a href="{{ route('admin.events.index') }}"
                        class="px-2.5 py-1.5 rounded-xl border border-stone-100 hover:border-amber-200 hover:bg-amber-50/40 transition flex items-center justify-between group">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-5 h-5 rounded-lg bg-amber-100/70 text-[#8c5a3c] flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-[11px] font-bold text-stone-700 group-hover:text-[#8c5a3c]">Event &
                                Birthday</span>
                        </div>
                        <svg class="w-3 h-3 text-stone-300 group-hover:text-[#8c5a3c] transition shrink-0" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- 4. Roblox Misi & Reward -->
                    <a href="{{ route('admin.roblox.index') }}"
                        class="px-2.5 py-1.5 rounded-xl border border-stone-100 hover:border-amber-200 hover:bg-amber-50/40 transition flex items-center justify-between group">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-5 h-5 rounded-lg bg-amber-100/70 text-[#8c5a3c] flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 5T6 7a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2H9zM6 12h4m-2-2v4m10 0h.01M16 10h.01" />
                                </svg>
                            </div>
                            <span class="text-[11px] font-bold text-stone-700 group-hover:text-[#8c5a3c]">Roblox Misi &
                                Reward</span>
                        </div>
                        <svg class="w-3 h-3 text-stone-300 group-hover:text-[#8c5a3c] transition shrink-0" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- 5. Kelola Karir / Pelamar -->
                    <a href="{{ route('admin.careers.index') }}"
                        class="px-2.5 py-1.5 rounded-xl border border-stone-100 hover:border-amber-200 hover:bg-amber-50/40 transition flex items-center justify-between group">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-5 h-5 rounded-lg bg-amber-100/70 text-[#8c5a3c] flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span class="text-[11px] font-bold text-stone-700 group-hover:text-[#8c5a3c]">Kelola Karir &
                                Pelamar</span>
                        </div>
                        <svg class="w-3 h-3 text-stone-300 group-hover:text-[#8c5a3c] transition shrink-0" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- 6. Berita & Blog -->
                    <a href="{{ route('admin.posts.index') }}"
                        class="px-2.5 py-1.5 rounded-xl border border-stone-100 hover:border-amber-200 hover:bg-amber-50/40 transition flex items-center justify-between group">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-5 h-5 rounded-lg bg-amber-100/70 text-[#8c5a3c] flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6m-6 4h6" />
                                </svg>
                            </div>
                            <span class="text-[11px] font-bold text-stone-700 group-hover:text-[#8c5a3c]">Berita &
                                Blog</span>
                        </div>
                        <svg class="w-3 h-3 text-stone-300 group-hover:text-[#8c5a3c] transition shrink-0" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

        </div>

    </div>
@endsection