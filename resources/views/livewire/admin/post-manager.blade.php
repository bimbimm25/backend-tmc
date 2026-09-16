<div 
    x-data="{ 
        modalPostOpen: @entangle('isOpen'),
        modalCategoryOpen: @entangle('isCategoryModalOpen')
    }"
    x-init="
        $watch('modalPostOpen', value => {
            if (value || modalCategoryOpen) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
        $watch('modalCategoryOpen', value => {
            if (value || modalPostOpen) {
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
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm rounded-2xl flex items-center justify-between shadow-xs transition">
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

    {{-- Filter & Action Bar --}}
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3.5 bg-white p-4 sm:p-5 rounded-3xl border border-stone-200/80 shadow-xs">
        
        <!-- Search & Filter Area -->
        <div class="flex flex-col sm:flex-row items-center gap-2.5 w-full sm:w-auto">
            <!-- Search Bar -->
            <div class="relative w-full sm:w-72">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Cari judul artikel..." 
                    class="w-full bg-stone-50 border border-stone-200 rounded-2xl pl-9 pr-4 py-2.5 text-xs text-stone-800 focus:bg-white focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition shadow-2xs font-medium placeholder:text-stone-400"
                >
                <svg class="w-4 h-4 text-stone-400 absolute left-3 top-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Select Filter Kategori -->
            <div class="w-full sm:w-auto">
                <select 
                    wire:model.live="filterCategory"
                    class="w-full bg-stone-50 border border-stone-200 rounded-2xl px-3.5 py-2.5 text-xs text-stone-700 focus:bg-white focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition shadow-2xs font-medium cursor-pointer"
                >
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->posts_count }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <button 
                wire:click="openCategoryModal"
                class="px-4 py-2.5 bg-stone-100 hover:bg-stone-200 active:bg-stone-300 text-stone-700 text-xs font-bold rounded-2xl transition duration-150 flex items-center justify-center gap-2 flex-1 sm:flex-initial cursor-pointer border border-stone-200/80 shadow-2xs"
            >
                <svg class="w-4 h-4 text-[#8c5a3c]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span>Kelola Kategori</span>
            </button>

            <button 
                wire:click="openModal"
                class="px-5 py-2.5 bg-[#8c5a3c] hover:bg-[#73482f] active:bg-[#5c3a25] text-white text-xs font-bold rounded-2xl transition duration-150 shadow-md shadow-[#8c5a3c]/15 flex items-center justify-center gap-2 flex-1 sm:flex-initial cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Tulis Artikel Baru</span>
            </button>
        </div>
    </div>

    <!-- TAMPILAN MOBILE (CARD LIST) -->
    <div class="block sm:hidden space-y-3">
        @forelse($posts as $item)
            <div class="bg-white p-4 rounded-3xl border border-stone-200/80 shadow-2xs flex items-center justify-between gap-3">
                <div class="flex items-center gap-3.5 min-w-0">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="w-14 h-14 object-cover rounded-2xl border border-stone-200 shrink-0">
                    @else
                        <div class="w-14 h-14 bg-stone-100 rounded-2xl flex items-center justify-center text-[9px] text-stone-400 border border-stone-200 shrink-0 font-semibold">
                            No Cover
                        </div>
                    @endif

                    <div class="min-w-0 space-y-1">
                        <div class="font-bold text-stone-900 text-xs truncate">{{ $item->title }}</div>
                        
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="px-2 py-0.5 text-[9px] bg-amber-50 text-amber-900 border border-amber-200/80 font-bold rounded-md">
                                {{ $item->category->name ?? 'Umum' }}
                            </span>

                            @if($item->is_published)
                                <span class="px-2 py-0.5 text-[9px] bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold rounded-md">Tayang</span>
                            @else
                                <span class="px-2 py-0.5 text-[9px] bg-stone-100 text-stone-600 font-medium rounded-md">Draft</span>
                            @endif
                        </div>

                        <div class="text-[10px] text-stone-400 font-mono truncate">/blog/{{ $item->slug }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-1 shrink-0">
                    <button wire:click="edit({{ $item->id }})" class="p-2 text-stone-600 hover:text-[#8c5a3c] bg-stone-50 rounded-xl transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button wire:click="delete({{ $item->id }})" wire:confirm="Hapus artikel ini?" class="p-2 text-stone-400 hover:text-rose-600 bg-stone-50 rounded-xl transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 text-center rounded-3xl border border-stone-200 text-stone-400 text-xs font-semibold">
                Belum ada artikel yang ditambahkan.
            </div>
        @endforelse
    </div>

    <!-- TAMPILAN DESKTOP (TABEL) -->
    <div class="hidden sm:block bg-white rounded-3xl shadow-xs border border-stone-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50/80 border-b border-stone-200/60 text-[11px] font-bold text-stone-400 uppercase tracking-wider">
                        <th class="py-4 px-4 w-20">Foto</th>
                        <th class="py-4 px-4">Judul Artikel & Link Google</th>
                        <th class="py-4 px-4">Kategori</th>
                        <th class="py-4 px-4">Pembaca</th>
                        <th class="py-4 px-4">Status</th>
                        <th class="py-4 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-xs">
                    @forelse($posts as $item)
                        <tr class="hover:bg-stone-50/60 transition duration-150">
                            <td class="py-3.5 px-4">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" class="w-14 h-10 object-cover rounded-2xl border border-stone-200 shadow-2xs">
                                @else
                                    <div class="w-14 h-10 bg-stone-100 rounded-2xl flex items-center justify-center text-[10px] text-stone-400 border border-stone-200 font-semibold">
                                        No Cover
                                    </div>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="font-bold text-stone-900 text-xs sm:text-sm">{{ $item->title }}</div>
                                <div class="text-[11px] text-[#8c5a3c] mt-0.5 font-mono">tomeetcafe.com/blog/{{ $item->slug }}</div>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 text-[10px] font-bold bg-[#f4ece1] text-[#8c5a3c] rounded-xl border border-[#e6ccb2]/60">
                                    {{ $item->category->name ?? 'Umum' }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 font-bold text-stone-800">
                                <div class="flex items-center gap-1.5 text-stone-600">
                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>{{ number_format($item->views ?? 0) }} Pembaca</span>
                                </div>
                            </td>

                            <td class="py-3.5 px-4">
                                @if($item->is_published)
                                    <span class="px-2.5 py-1 text-[10px] bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold rounded-lg">Tayang</span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] bg-stone-100 text-stone-600 rounded-lg font-medium">Draft</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button wire:click="edit({{ $item->id }})" class="p-2 text-stone-600 hover:text-[#8c5a3c] hover:bg-amber-50 rounded-xl transition cursor-pointer" title="Edit Artikel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="delete({{ $item->id }})" wire:confirm="Hapus artikel ini?" class="p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition cursor-pointer" title="Hapus Artikel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-stone-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-8 h-8 text-stone-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6m-6 4h6"/></svg>
                                    <p class="text-xs font-semibold">Belum ada artikel ditambahkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pt-2">
        {{ $posts->links() }}
    </div>

    {{-- ==================================================== --}}
    {{-- MODAL 1: FORM TAMBAH / EDIT ARTIKEL                  --}}
    {{-- ==================================================== --}}
    @if($isOpen)
        <div 
            x-data="{ activeTab: 'content' }"
            class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900/40 backdrop-blur-xs p-3 sm:p-4 overflow-hidden"
        >
            <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden border border-stone-100 transform transition-all my-auto flex flex-col max-h-[90vh]">
                
                <!-- Modal Header -->
                <div class="px-5 py-4 border-b border-stone-100 flex items-center justify-between bg-stone-50/70">
                    <div>
                        <h3 class="font-bold text-stone-900 text-sm sm:text-base">
                            {{ $postId ? 'Edit Artikel Blog' : 'Tulis Artikel Blog Baru' }}
                        </h3>
                        <p class="text-[11px] text-stone-500 font-medium mt-0.5">Kelola isi pembahasan dan optimalkan pencarian Google dengan mudah.</p>
                    </div>
                    <button type="button" wire:click="closeModal" class="p-1.5 text-stone-400 hover:text-stone-700 rounded-xl hover:bg-stone-100 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Tab Navigation -->
                <div class="flex border-b border-stone-200/80 px-5 bg-white text-xs font-bold shrink-0">
                    <button 
                        type="button"
                        @click="activeTab = 'content'"
                        :class="activeTab === 'content' ? 'border-[#8c5a3c] text-[#8c5a3c]' : 'border-transparent text-stone-500 hover:text-stone-800'"
                        class="py-3 px-3 border-b-2 flex items-center gap-2 transition cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>1. Konten Utama</span>
                    </button>

                    <button 
                        type="button"
                        @click="activeTab = 'seo'"
                        :class="activeTab === 'seo' ? 'border-[#8c5a3c] text-[#8c5a3c]' : 'border-transparent text-stone-500 hover:text-stone-800'"
                        class="py-3 px-3 border-b-2 flex items-center gap-2 transition cursor-pointer relative"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>2. Tampilan di Google (SEO)</span>
                        @if($meta_description)
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        @endif
                    </button>
                </div>

                <!-- Modal Body Form -->
                <form wire:submit.prevent="save" class="p-5 overflow-y-auto space-y-4 flex-1">
                    
                    <!-- TAB 1: KONTEN UTAMA ARTIKEL -->
                    <div x-show="activeTab === 'content'" class="space-y-4">
                        
                        <!-- Judul Artikel -->
                        <div>
                            <label class="block text-xs font-bold text-stone-800 mb-1">
                                Judul Artikel <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model.live="title"
                                class="w-full border border-stone-200 rounded-2xl px-3.5 py-2.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-semibold"
                                placeholder="Contoh: 5 Menu Minuman Favorit Anak di To Meet Cafe"
                            >
                            <p class="text-[10.5px] text-stone-400 mt-1">Buat judul yang menarik dan mudah dipahami pengunjung.</p>
                            @error('title') <span class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kategori & Alamat URL (Slug) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-stone-800 mb-1">
                                    Pilih Kategori Blog
                                </label>
                                <select 
                                    wire:model="category_id"
                                    class="w-full border border-stone-200 rounded-2xl px-3.5 py-2.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium cursor-pointer"
                                >
                                    <option value="">Pilih Kategori...</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-800 mb-1">
                                    Alamat URL Halaman (Slug)
                                </label>
                                <div class="flex items-center bg-stone-50 border border-stone-200 rounded-2xl px-3 py-2 text-xs text-stone-500 font-mono">
                                    <span class="text-stone-400 select-none text-[11px]">/blog/</span>
                                    <input 
                                        type="text" 
                                        wire:model="slug"
                                        class="w-full bg-transparent text-stone-800 font-bold focus:outline-none pl-1 text-xs"
                                        placeholder="judul-artikel"
                                    >
                                </div>
                                @error('slug') <span class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Isi Konten Artikel -->
                        <div>
                            <label class="block text-xs font-bold text-stone-800 mb-1">
                                Isi Pembahasan Artikel <span class="text-rose-500">*</span>
                            </label>
                            <textarea 
                                wire:model.live="content" 
                                rows="7"
                                class="w-full border border-stone-200 rounded-2xl p-3.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition leading-relaxed font-sans"
                                placeholder="Tuliskan cerita, informasi, atau tips menarik Anda di sini..."
                            ></textarea>
                            @error('content') <span class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Upload Foto Sampul -->
                        <div>
                            <label class="block text-xs font-bold text-stone-800 mb-1">Foto Sampul Artikel (Thumbnail)</label>
                            <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-2xl border border-stone-200/80">
                                <input type="file" wire:model="image" accept="image/*" class="w-full text-xs text-stone-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-[#8c5a3c] hover:file:bg-amber-100 transition cursor-pointer">

                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="w-14 h-10 object-cover rounded-xl border border-stone-200 shadow-2xs shrink-0">
                                @elseif ($oldImage)
                                    <img src="{{ asset('storage/' . $oldImage) }}" class="w-14 h-10 object-cover rounded-xl border border-stone-200 shadow-2xs shrink-0">
                                @endif
                            </div>
                            @error('image') <span class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status Tayang -->
                        <div class="pt-1">
                            <label class="flex items-center gap-2.5 text-xs font-bold text-stone-700 cursor-pointer">
                                <input type="checkbox" wire:model="is_published" class="rounded-md border-stone-300 text-[#8c5a3c] focus:ring-[#8c5a3c] w-4 h-4">
                                <span>Langsung Tayangkan Artikel Ini ke Website</span>
                            </label>
                        </div>
                    </div>

                    <!-- TAB 2: PENGATURAN GOOGLE (SEO OPTIMIZER) -->
                    <div x-show="activeTab === 'seo'" class="space-y-4" style="display: none;">
                        
                        <!-- Panduan Singkat -->
                        <div class="p-3.5 bg-amber-50 border border-amber-200/80 rounded-2xl flex items-start gap-2.5 text-xs text-amber-900 font-medium">
                            <svg class="w-4 h-4 text-amber-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p>Kolom di bawah membantu artikel Anda lebih mudah ditemukan oleh orang yang mencari di Google.</p>
                        </div>

                        <!-- Live Google Snippet Box -->
                        <div class="space-y-1.5">
                            <span class="text-[11px] font-bold text-stone-500 uppercase tracking-wider block">
                                Tampilan Jika Muncul di Hasil Pencarian Google:
                            </span>
                            
                            <div class="bg-white p-4 rounded-2xl border border-stone-300 shadow-xs space-y-1 font-sans">
                                <div class="flex items-center gap-1.5 text-[11px] text-[#202124]">
                                    <div class="w-4 h-4 rounded-full bg-stone-100 flex items-center justify-center text-[8px] font-bold text-[#8c5a3c] border border-stone-200">
                                        TM
                                    </div>
                                    <span class="text-[#202124] font-medium text-[11px]">To Meet Cafe</span>
                                    <span class="text-stone-400 text-[10px] truncate">• tomeetcafe.com &gt; blog &gt; {{ $slug ?: 'judul-artikel' }}</span>
                                </div>
                                
                                <h4 class="text-sm sm:text-base font-semibold text-[#1a0dab] hover:underline cursor-pointer leading-snug line-clamp-1">
                                    {{ $meta_title ?: ($title ?: 'Judul Artikel Anda - To Meet Cafe') }}
                                </h4>

                                <p class="text-xs text-[#4d5156] leading-relaxed line-clamp-2">
                                    {{ $meta_description ?: 'Tuliskan rangkuman 1-2 kalimat pada kolom Meta Deskripsi di bawah agar orang tertarik mengklik artikel ini.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Kata Kunci Target & Judul Google -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                            <div>
                                <label class="block text-xs font-bold text-stone-800 mb-1">
                                    Kata Kunci Pencarian (Target Keyword)
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="focus_keyword"
                                    class="w-full border border-stone-200 rounded-2xl px-3.5 py-2.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] transition"
                                    placeholder="Contoh: cafe ramah anak sidoarjo"
                                >
                                <p class="text-[10px] text-stone-400 mt-1">Kata apa yang kira-kira diketik orang di Google.</p>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-xs font-bold text-stone-800">
                                        Judul di Google (Meta Title)
                                    </label>
                                    <span class="text-[10px] font-bold {{ strlen($meta_title) > 60 ? 'text-rose-600' : 'text-stone-400' }}">
                                        {{ strlen($meta_title) }}/60
                                    </span>
                                </div>
                                <input 
                                    type="text" 
                                    wire:model.live="meta_title"
                                    class="w-full border border-stone-200 rounded-2xl px-3.5 py-2.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] transition"
                                    placeholder="Bisa disamakan dengan judul artikel"
                                >
                            </div>
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-stone-800">
                                    Rangkuman untuk Google (Meta Description)
                                </label>
                                <span class="text-[10px] font-bold {{ strlen($meta_description) >= 100 && strlen($meta_description) <= 160 ? 'text-emerald-600' : (strlen($meta_description) > 160 ? 'text-rose-600' : 'text-stone-400') }}">
                                    {{ strlen($meta_description) }}/160 Karakter
                                </span>
                            </div>
                            <textarea 
                                wire:model.live="meta_description" 
                                rows="3"
                                class="w-full border border-stone-200 rounded-2xl p-3 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] transition resize-none leading-relaxed"
                                placeholder="Jelaskan secara singkat isi artikel ini dalam 1-2 kalimat yang menarik..."
                            ></textarea>
                            @error('meta_description') <span class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <!-- Modal Footer Actions -->
                    <div class="pt-4 flex items-center justify-between border-t border-stone-100 mt-4">
                        <button 
                            type="button" 
                            wire:click="closeModal"
                            class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-2xl transition cursor-pointer"
                        >
                            Batal
                        </button>
                        
                        <div class="flex items-center gap-2">
                            <button 
                                type="submit"
                                class="px-6 py-2.5 bg-[#8c5a3c] hover:bg-[#73482f] text-white text-xs font-bold rounded-2xl shadow-md transition cursor-pointer flex items-center gap-1.5"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>Simpan & Publikasikan</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    @endif

    {{-- ==================================================== --}}
    {{-- MODAL 2: KELOLA KATEGORI BLOG                        --}}
    {{-- ==================================================== --}}
    @if($isCategoryModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900/40 backdrop-blur-xs p-3 sm:p-4 overflow-hidden">
            <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden border border-stone-100 transform transition-all my-auto">
                <div class="px-5 py-4 border-b border-stone-100 flex items-center justify-between bg-stone-50/70">
                    <div>
                        <h3 class="font-bold text-stone-900 text-sm">Kelola Kategori Blog</h3>
                        <p class="text-[11px] text-stone-500 font-medium">Buat kategori baru untuk mengelompokkan artikel.</p>
                    </div>
                    <button type="button" wire:click="closeCategoryModal" class="p-1.5 text-stone-400 hover:text-stone-700 rounded-xl hover:bg-stone-100 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-5 space-y-4">
                    <!-- Form Tambah / Edit Kategori -->
                    <form wire:submit.prevent="saveCategory" class="flex gap-2">
                        <div class="flex-1">
                            <input 
                                type="text" 
                                wire:model="categoryName"
                                placeholder="Ketik nama kategori baru..."
                                class="w-full border border-stone-200 rounded-2xl px-3.5 py-2 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium"
                            >
                            @error('categoryName') <span class="text-[10px] text-rose-600 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-[#8c5a3c] hover:bg-[#73482f] text-white text-xs font-bold rounded-2xl shadow-sm transition shrink-0 cursor-pointer"
                        >
                            {{ $categoryId ? 'Simpan' : 'Tambah' }}
                        </button>
                    </form>

                    <!-- List Daftar Kategori -->
                    <div class="border-t border-stone-100 pt-3 space-y-2 max-h-60 overflow-y-auto">
                        <span class="text-[10.5px] font-bold text-stone-400 uppercase tracking-wider block">Kategori yang Sudah Ada:</span>
                        
                        @forelse($categories as $cat)
                            <div class="p-3 bg-stone-50 rounded-2xl border border-stone-200/60 flex items-center justify-between text-xs font-semibold">
                                <div class="flex items-center gap-2">
                                    <span class="text-stone-800">{{ $cat->name }}</span>
                                    <span class="px-2 py-0.5 text-[9px] bg-white border border-stone-200 rounded-md text-stone-500 font-mono">
                                        {{ $cat->posts_count }} artikel
                                    </span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button 
                                        wire:click="editCategory({{ $cat->id }})" 
                                        class="p-1.5 text-stone-500 hover:text-[#8c5a3c] hover:bg-stone-200 rounded-lg transition cursor-pointer"
                                        title="Ubah Nama"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button 
                                        wire:click="deleteCategory({{ $cat->id }})" 
                                        wire:confirm="Hapus kategori ini? (Artikel di dalamnya tidak akan terhapus)"
                                        class="p-1.5 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer"
                                        title="Hapus"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-stone-400 text-center py-4">Belum ada kategori yang dibuat.</p>
                        @endforelse
                    </div>
                </div>

                <div class="px-5 py-3.5 border-t border-stone-100 bg-stone-50/70 flex justify-end">
                    <button 
                        type="button" 
                        wire:click="closeCategoryModal"
                        class="px-4 py-2 bg-stone-200 hover:bg-stone-300 text-stone-700 text-xs font-bold rounded-2xl transition cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>