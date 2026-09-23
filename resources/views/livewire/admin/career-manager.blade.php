<div 
    x-data="{ 
        modalCareerOpen: @entangle('isOpen') 
    }"
    x-init="
        $watch('modalCareerOpen', value => {
            if (value) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    "
    class="space-y-4 sm:space-y-6 text-stone-800"
>
    {{-- Toast Notification --}}
    @if($successMessage)
        <div class="p-4 bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs sm:text-sm rounded-xl flex items-center justify-between shadow-xs transition">
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="font-semibold">{{ $successMessage }}</span>
            </div>
            <button wire:click="$set('successMessage', '')" class="text-emerald-500 hover:text-emerald-800 p-1 rounded-lg transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Tabs Navigation --}}
    <div class="flex border-b border-stone-200 bg-white rounded-2xl px-2 pt-2 shadow-xs">
        <button 
            wire:click="$set('activeTab', 'positions')" 
            class="pb-3 px-4 sm:px-6 text-xs sm:text-sm font-bold border-b-2 transition duration-150 flex items-center gap-2 cursor-pointer {{ $activeTab === 'positions' ? 'border-[#8c5a3c] text-[#8c5a3c]' : 'border-transparent text-stone-500 hover:text-stone-800' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span>Lowongan Karir</span>
        </button>
        <button 
            wire:click="$set('activeTab', 'applicants')" 
            class="pb-3 px-4 sm:px-6 text-xs sm:text-sm font-bold border-b-2 transition duration-150 flex items-center gap-2 cursor-pointer {{ $activeTab === 'applicants' ? 'border-[#8c5a3c] text-[#8c5a3c]' : 'border-transparent text-stone-500 hover:text-stone-800' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <span>Data Pelamar</span>
        </button>
    </div>

    {{-- Search & Action Bar --}}
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3.5 bg-white p-4 sm:p-5 rounded-2xl border border-stone-200/80 shadow-xs">
        <div class="relative w-full sm:w-72">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="{{ $activeTab === 'positions' ? 'Cari lowongan...' : 'Cari nama/email pelamar...' }}" 
                class="w-full bg-stone-50/80 border border-stone-200 rounded-xl pl-9 pr-4 py-2 text-xs text-stone-800 focus:bg-white focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition shadow-2xs font-medium placeholder:text-stone-400"
            >
            <svg class="w-4 h-4 text-stone-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>

        @if($activeTab === 'positions')
            <button 
                wire:click="openModal" 
                class="px-4 py-2 bg-[#8c5a3c] hover:bg-[#73482f] active:bg-[#5c3a25] text-white text-xs font-bold rounded-xl transition duration-150 shadow-md shadow-[#8c5a3c]/15 flex items-center justify-center gap-2 w-full sm:w-auto cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Buka Lowongan Baru</span>
            </button>
        @endif
    </div>

    {{-- TAB 1: LIST POSISI KARIR --}}
    @if($activeTab === 'positions')
        <!-- TAMPILAN MOBILE (CARD LIST) -->
        <div class="block sm:hidden space-y-3">
            @forelse($careers as $item)
                <div class="bg-white p-3.5 rounded-2xl border border-stone-200/80 shadow-2xs flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="font-bold text-stone-900 text-xs truncate">{{ $item->title }}</div>
                        <div class="text-[10px] text-stone-500 font-medium">{{ $item->department }} • {{ $item->location_name }}</div>
                        
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="px-1.5 py-0.2 text-[8px] bg-amber-50 text-amber-800 border border-amber-200 font-bold rounded">{{ $item->type }}</span>
                            <span class="px-1.5 py-0.2 text-[8px] bg-stone-100 text-stone-700 font-bold rounded">{{ $item->applications_count }} Pelamar</span>
                            @if($item->is_active)
                                <span class="px-1.5 py-0.2 text-[8px] bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold rounded">Buka</span>
                            @else
                                <span class="px-1.5 py-0.2 text-[8px] bg-rose-50 text-rose-700 border border-rose-200 font-bold rounded">Tutup</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-1 shrink-0">
                        <button wire:click="edit({{ $item->id }})" class="p-2 text-stone-600 hover:text-[#8c5a3c] bg-stone-50 rounded-xl transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="deleteCareer({{ $item->id }})" wire:confirm="Yakin hapus lowongan ini?" class="p-2 text-stone-400 hover:text-rose-600 bg-stone-50 rounded-xl transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="bg-white p-8 text-center rounded-2xl border border-stone-200 text-stone-400 text-xs font-semibold">
                    Belum ada lowongan dibuka.
                </div>
            @endforelse
        </div>

        <!-- TAMPILAN DESKTOP (TABEL) -->
        <div class="hidden sm:block bg-white rounded-2xl shadow-xs border border-stone-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-stone-50/80 border-b border-stone-200/60 text-[11px] font-bold text-stone-400 uppercase tracking-wider">
                            <th class="py-3.5 px-4">Posisi & Departemen</th>
                            <th class="py-3.5 px-4">Tipe & Lokasi</th>
                            <th class="py-3.5 px-4">Jumlah Pelamar</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 text-xs">
                        @forelse($careers as $item)
                            <tr class="hover:bg-stone-50/60 transition duration-150">
                                <td class="py-3 px-4">
                                    <div class="font-bold text-stone-900 text-xs sm:text-sm">{{ $item->title }}</div>
                                    <div class="text-[11px] text-stone-400 mt-0.5">{{ $item->department }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 text-[10px] bg-amber-50 text-amber-800 border border-amber-200/80 font-bold rounded-md">
                                        {{ $item->type }}
                                    </span>
                                    <div class="text-[11px] text-stone-500 mt-1">{{ $item->location_name }}</div>
                                </td>
                                <td class="py-3 px-4 font-extrabold text-stone-800">
                                    {{ $item->applications_count }} Pelamar
                                </td>
                                <td class="py-3 px-4">
                                    @if($item->is_active)
                                        <span class="px-2 py-0.5 text-[10px] bg-emerald-50 text-emerald-800 border border-emerald-200/80 font-bold rounded-md">Buka</span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] bg-rose-50 text-rose-700 border border-rose-200/80 font-bold rounded-md">Tutup</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button wire:click="edit({{ $item->id }})" class="p-1.5 text-stone-600 hover:text-[#8c5a3c] hover:bg-amber-50 rounded-lg transition cursor-pointer" title="Edit Lowongan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button wire:click="deleteCareer({{ $item->id }})" wire:confirm="Yakin hapus lowongan ini?" class="p-1.5 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Hapus Lowongan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-stone-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg class="w-8 h-8 text-stone-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <p class="text-xs font-semibold">Belum ada lowongan dibuka.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pt-2">{{ $careers->links() }}</div>
    @endif

    {{-- TAB 2: LIST PELAMAR --}}
    @if($activeTab === 'applicants')
        <!-- TAMPILAN MOBILE -->
        <div class="block sm:hidden space-y-3">
            @forelse($applicants as $app)
                <div class="bg-white p-3.5 rounded-2xl border border-stone-200/80 shadow-2xs flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="font-bold text-stone-900 text-xs truncate">{{ $app->full_name }}</div>
                        <div class="text-[10px] font-bold text-[#8c5a3c] mt-0.5">{{ $app->career->title ?? 'Posisi Dihapus' }}</div>
                        <div class="text-[10px] text-stone-500 mt-0.5">{{ $app->email }} • {{ $app->phone }}</div>
                        
                        <div class="mt-2">
                            @if($app->resume_path)
                                <a href="{{ asset('storage/' . $app->resume_path) }}" target="_blank" class="px-2.5 py-1 bg-amber-50 text-[#8c5a3c] text-[10px] font-bold rounded-lg border border-amber-200 inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Download CV</span>
                                </a>
                            @else
                                <span class="text-[10px] text-stone-400">Tanpa File</span>
                            @endif
                        </div>
                    </div>

                    <div class="shrink-0">
                        <button wire:click="deleteApplicant({{ $app->id }})" wire:confirm="Hapus data pelamar ini?" class="p-2 text-stone-400 hover:text-rose-600 bg-stone-50 rounded-xl transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="bg-white p-8 text-center rounded-2xl border border-stone-200 text-stone-400 text-xs font-semibold">
                    Belum ada pelamar masuk.
                </div>
            @endforelse
        </div>

        <!-- TAMPILAN DESKTOP -->
        <div class="hidden sm:block bg-white rounded-2xl shadow-xs border border-stone-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-stone-50/80 border-b border-stone-200/60 text-[11px] font-bold text-stone-400 uppercase tracking-wider">
                            <th class="py-3.5 px-4">Nama Pelamar</th>
                            <th class="py-3.5 px-4">Posisi Dilamar</th>
                            <th class="py-3.5 px-4">Kontak</th>
                            <th class="py-3.5 px-4">Berkas CV</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 text-xs">
                        @forelse($applicants as $app)
                            <tr class="hover:bg-stone-50/60 transition duration-150">
                                <td class="py-3 px-4">
                                    <div class="font-bold text-stone-900 text-xs sm:text-sm">{{ $app->full_name }}</div>
                                    <div class="text-[11px] text-stone-400 mt-0.5">{{ $app->created_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="py-3 px-4 font-bold text-[#8c5a3c]">
                                    {{ $app->career->title ?? 'Posisi Dihapus' }}
                                </td>
                                <td class="py-3 px-4 text-stone-700">
                                    <div>{{ $app->email }}</div>
                                    <div class="text-[11px] text-stone-400 mt-0.5">{{ $app->phone }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    @if($app->resume_path)
                                        <a href="{{ asset('storage/' . $app->resume_path) }}" target="_blank" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-[#8c5a3c] text-[11px] font-bold rounded-lg border border-amber-200 inline-flex items-center gap-1 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>Download CV</span>
                                        </a>
                                    @else
                                        <span class="text-[11px] text-stone-400">Tanpa File</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <button wire:click="deleteApplicant({{ $app->id }})" wire:confirm="Hapus data pelamar ini?" class="p-1.5 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Hapus Pelamar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-stone-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg class="w-8 h-8 text-stone-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <p class="text-xs font-semibold">Belum ada pelamar masuk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pt-2">{{ $applicants->links() }}</div>
    @endif

    {{-- MODAL FORM LOWONGAN --}}
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900/40 backdrop-blur-xs p-3 sm:p-4 overflow-hidden">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden border border-stone-100 transform transition-all my-auto">
                
                <!-- Modal Header -->
                <div class="px-5 py-3.5 border-b border-stone-100 flex items-center justify-between bg-stone-50/60">
                    <div>
                        <h3 class="font-bold text-stone-900 text-sm">
                            {{ $careerId ? 'Edit Lowongan Pekerjaan' : 'Buka Lowongan Pekerjaan Baru' }}
                        </h3>
                        <p class="text-[10px] text-stone-500 font-medium">Isi kriteria detail posisi tim cafe.</p>
                    </div>
                    <button type="button" wire:click="closeModal" class="p-1 text-stone-400 hover:text-stone-700 rounded-lg hover:bg-stone-100 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body Form -->
                <form wire:submit.prevent="save" class="p-4 sm:p-5 space-y-3.5 max-h-[80vh] overflow-y-auto">
                    
                    <!-- Judul Posisi -->
                    <div>
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Judul Posisi / Pekerjaan</label>
                        <input 
                            type="text" 
                            wire:model="title" 
                            class="w-full border border-stone-200 rounded-xl px-3 py-1.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium" 
                            placeholder="Head Barista / Pastry Chef / Floor Staff"
                        >
                        @error('title') <span class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Departemen & Tipe Kerja -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Departemen</label>
                            <input 
                                type="text" 
                                wire:model="department" 
                                class="w-full border border-stone-200 rounded-xl px-3 py-1.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium" 
                                placeholder="Kitchen / Barista / Service"
                            >
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Tipe Kerja</label>
                            <select 
                                wire:model="type" 
                                class="w-full border border-stone-200 rounded-xl px-3 py-1.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium"
                            >
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Contract">Contract</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>
                    </div>

                    <!-- Lokasi Penempatan (Terpisah: Central Kitchen dan Office To Meet Cafe) -->
                    <div>
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Lokasi Outlet Penempatan</label>
                        <select 
                            wire:model="location_name" 
                            class="w-full border border-stone-200 rounded-xl px-3 py-1.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium"
                        >
                            <option value="Central Kitchen">Central Kitchen</option>
                            <option value="Office To Meet Cafe">Office To Meet Cafe</option>
                            <option value="Pondok Mutiara">Pondok Mutiara</option>
                            <option value="Heavenland Park">Heavenland Park</option>
                            <option value="Semua Lokasi">Semua Lokasi</option>
                        </select>
                    </div>

                    <!-- Deskripsi Pekerjaan (Multi-line / Enter) -->
                    <div>
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Deskripsi Pekerjaan</label>
                        <textarea 
                            wire:model="description" 
                            rows="3" 
                            class="w-full border border-stone-200 rounded-xl p-2.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition resize-y font-medium leading-relaxed placeholder:text-stone-400" 
                            placeholder="Tugas utama dan tanggung jawab harian..."
                        ></textarea>
                        @error('description') <span class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Persyaratan (Requirements) (Multi-line / Enter) -->
                    <div>
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Persyaratan (Requirements)</label>
                        <textarea 
                            wire:model="requirements" 
                            rows="3" 
                            class="w-full border border-stone-200 rounded-xl p-2.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition resize-y font-medium leading-relaxed placeholder:text-stone-400" 
                            placeholder="Kriteria pengalaman, kualifikasi, atau keahlian khusus..."
                        ></textarea>
                    </div>

                    <!-- Benefit Pekerjaan (Multi-line / Enter) -->
                    <div>
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Benefit Pekerjaan</label>
                        <textarea 
                            wire:model="benefits" 
                            rows="3" 
                            class="w-full border border-stone-200 rounded-xl p-2.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition resize-y font-medium leading-relaxed placeholder:text-stone-400" 
                            placeholder="Gaji kompetitif, BPJS, meal allowance, jenjang karir..."
                        ></textarea>
                    </div>

                    <!-- Status Buka / Tutup -->
                    <div class="pt-1">
                        <label class="flex items-center gap-2 text-xs font-semibold text-stone-700 cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="rounded border-stone-300 text-[#8c5a3c] focus:ring-[#8c5a3c] w-3.5 h-3.5">
                            <span>Buka Lowongan Ini (Status Aktif)</span>
                        </label>
                    </div>

                    <!-- Form Footer Buttons -->
                    <div class="pt-3 flex justify-end gap-2 border-t border-stone-100">
                        <button 
                            type="button" 
                            wire:click="closeModal" 
                            class="px-3.5 py-1.5 bg-stone-100 hover:bg-stone-200/80 text-stone-700 text-xs font-bold rounded-xl transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-1.5 bg-[#8c5a3c] hover:bg-[#73482f] text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer"
                        >
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>