<div x-data="{ 
        modalMenuOpen: @entangle('isOpen'),
        modalCategoryOpen: @entangle('isCategoryOpen')
    }" x-init="
        $watch('modalMenuOpen', value => {
            if (value || modalCategoryOpen) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
        $watch('modalCategoryOpen', value => {
            if (value || modalMenuOpen) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    " class="space-y-6 text-stone-800">

    {{-- Toast Flash Notification --}}
    @if($successMessage)
        <div
            class="p-4 bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs sm:text-sm rounded-xl flex items-center justify-between shadow-xs transition">
            <div class="flex items-center gap-2.5">
                <div
                    class="w-6 h-6 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="font-semibold">{{ $successMessage }}</span>
            </div>
            <button wire:click="$set('successMessage', '')"
                class="text-emerald-500 hover:text-emerald-800 p-1 rounded-lg transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Header Action Bar --}}
    <div
        class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3.5 bg-white p-4 sm:p-5 rounded-2xl border border-stone-200/80 shadow-xs">

        <!-- Search & Filter Area -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full lg:w-auto">
            <!-- Search Bar -->
            <div class="relative w-full sm:w-60">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama menu..."
                    class="w-full bg-stone-50/80 border border-stone-200 rounded-xl pl-9 pr-4 py-2 text-xs text-stone-800 focus:bg-white focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition shadow-2xs font-medium placeholder:text-stone-400">
                <svg class="w-4 h-4 text-stone-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Select Filter Kategori Dinamis -->
            <div class="w-full sm:w-auto">
                <select wire:model.live="filterCategory"
                    class="w-full bg-stone-50/80 border border-stone-200 rounded-xl px-3 py-2 text-xs font-medium text-stone-700 focus:bg-white focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition shadow-2xs cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Select Filter Lokasi / Outlet -->
            <div class="w-full sm:w-auto">
                <select wire:model.live="filterLocation"
                    class="w-full bg-stone-50/80 border border-stone-200 rounded-xl px-3 py-2 text-xs font-medium text-stone-700 focus:bg-white focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition shadow-2xs cursor-pointer">
                    <option value="">Semua Lokasi</option>
                    <option value="all">Tersedia di Semua Outlet</option>
                    <option value="heavenland">Heavenland Park Saja</option>
                    <option value="pondok_mutiara">Pondok Mutiara Saja</option>
                </select>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2 sm:gap-2.5 w-full sm:w-auto flex-col sm:flex-row">
            <!-- Button Kelola Kategori -->
            <button wire:click="openCategoryModal"
                class="w-full sm:w-auto px-3.5 py-2 bg-stone-100 hover:bg-stone-200/80 text-stone-700 text-xs font-bold rounded-xl transition duration-150 border border-stone-200/80 flex items-center justify-center gap-2 shrink-0 cursor-pointer">
                <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                <span>Kelola Kategori</span>
            </button>

            <!-- Button Tambah Menu -->
            <button wire:click="openModal"
                class="w-full sm:w-auto px-4 py-2 bg-[#8c5a3c] hover:bg-[#73482f] active:bg-[#5c3a25] text-white text-xs font-bold rounded-xl transition duration-150 shadow-md shadow-[#8c5a3c]/15 flex items-center justify-center gap-2 shrink-0 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Menu Baru</span>
            </button>
        </div>

    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl shadow-xs border border-stone-200/80 overflow-hidden">

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-stone-50/80 border-b border-stone-200/60 text-[11px] font-bold text-stone-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-16">Foto</th>
                        <th class="py-3.5 px-4">Nama & Kategori</th>
                        <th class="py-3.5 px-4">Lokasi Outlet</th>
                        <th class="py-3.5 px-4">Harga</th>
                        <th class="py-3.5 px-4">Opsi Pembelian</th>
                        <th class="py-3.5 px-4">Label Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-xs">
                    @forelse($menus as $menu)
                        <tr class="hover:bg-stone-50/60 transition duration-150">
                            <!-- Image Thumbnail -->
                            <td class="py-3 px-4">
                                @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}"
                                        class="w-10 h-10 object-cover rounded-xl border border-stone-200 shadow-2xs">
                                @else
                                    <div
                                        class="w-10 h-10 bg-stone-100 rounded-xl flex items-center justify-center text-[10px] text-stone-400 border border-stone-200/80 font-semibold">
                                        No Image
                                    </div>
                                @endif
                            </td>

                            <!-- Name & Category -->
                            <td class="py-3 px-4">
                                <div class="font-bold text-stone-900 text-xs sm:text-sm">{{ $menu->name }}</div>
                                <div class="text-[11px] font-semibold text-[#8c5a3c] mt-0.5">{{ $menu->category }}</div>
                            </td>

                            <!-- Location Badge -->
                            <td class="py-3 px-4">
                                @if($menu->location === 'heavenland')
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200/80 rounded-lg">
                                        <span>Heavenland Park</span>
                                    </span>
                                @elseif($menu->location === 'pondok_mutiara')
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200/80 rounded-lg">
                                        <span>Pondok Mutiara</span>
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/80 rounded-lg">
                                        <span>Semua Outlet</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Price -->
                            <td class="py-3 px-4 font-extrabold text-stone-800">
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </td>

                            <!-- Opsi Pembelian -->
                            <td class="py-3 px-4">
                                <span
                                    class="px-2.5 py-1 text-[10px] font-bold bg-stone-100 text-stone-700 rounded-lg border border-stone-200">
                                    {{ $menu->purchase_option ?? 'Dine In Only' }}
                                </span>
                            </td>

                            <!-- Badges -->
                            <td class="py-3 px-4">
                                <div class="flex flex-wrap gap-1">
                                    @if($menu->is_featured)
                                        <span
                                            class="px-2 py-0.5 text-[10px] bg-purple-50 text-purple-800 border border-purple-200/80 font-bold rounded-md">
                                            Featured
                                        </span>
                                    @endif
                                    @if($menu->is_bestseller)
                                        <span
                                            class="px-2 py-0.5 text-[10px] bg-amber-50 text-amber-800 border border-amber-200/80 font-bold rounded-md">
                                            Best Seller
                                        </span>
                                    @endif
                                    @if($menu->is_recommended)
                                        <span
                                            class="px-2 py-0.5 text-[10px] bg-emerald-50 text-emerald-800 border border-emerald-200/80 font-bold rounded-md">
                                            Rekomendasi
                                        </span>
                                    @endif
                                    @if(!$menu->is_bestseller && !$menu->is_recommended && !$menu->is_featured)
                                        <span class="text-[11px] text-stone-400 font-normal">-</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Action Buttons -->
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="edit({{ $menu->id }})"
                                        class="p-1.5 text-stone-600 hover:text-[#8c5a3c] hover:bg-amber-50 rounded-lg transition cursor-pointer"
                                        title="Edit Menu">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $menu->id }})"
                                        wire:confirm="Yakin ingin menghapus menu {{ $menu->name }}?"
                                        class="p-1.5 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer"
                                        title="Hapus Menu">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-stone-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-8 h-8 text-stone-300" fill="none" stroke="currentColor" stroke-width="1.5"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-xs font-semibold">Data menu tidak ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List View -->
        <div class="block sm:hidden divide-y divide-stone-100">
            @forelse($menus as $menu)
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            @if($menu->image)
                                <img src="{{ asset('storage/' . $menu->image) }}"
                                    class="w-12 h-12 object-cover rounded-xl border border-stone-200 shrink-0">
                            @else
                                <div
                                    class="w-12 h-12 bg-stone-100 rounded-xl flex items-center justify-center text-[9px] text-stone-400 border border-stone-200 shrink-0 font-medium">
                                    No Image</div>
                            @endif
                            <div>
                                <h4 class="font-bold text-stone-900 text-sm leading-tight">{{ $menu->name }}</h4>
                                <div class="text-[11px] font-semibold text-[#8c5a3c] mt-0.5">{{ $menu->category }}</div>
                                <div class="text-xs font-extrabold text-stone-800 mt-1">Rp
                                    {{ number_format($menu->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-1 shrink-0">
                            <button wire:click="edit({{ $menu->id }})"
                                class="p-2 text-stone-600 hover:text-[#8c5a3c] hover:bg-amber-50 rounded-xl transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="delete({{ $menu->id }})"
                                wire:confirm="Yakin ingin menghapus menu {{ $menu->name }}?"
                                class="p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-1 pt-1">
                        <!-- Location Badge -->
                        @if($menu->location === 'heavenland')
                            <span
                                class="px-2 py-0.5 text-[9px] bg-amber-50 text-amber-800 border border-amber-200 font-bold rounded-md">Heavenland
                                Park</span>
                        @elseif($menu->location === 'pondok_mutiara')
                            <span
                                class="px-2 py-0.5 text-[9px] bg-blue-50 text-blue-800 border border-blue-200 font-bold rounded-md">Pondok
                                Mutiara</span>
                        @else
                            <span
                                class="px-2 py-0.5 text-[9px] bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold rounded-md">Semua
                                Outlet</span>
                        @endif

                        <!-- Opsi Pembelian Badge Mobile -->
                        <span
                            class="px-2 py-0.5 text-[9px] bg-stone-100 text-stone-700 border border-stone-200 font-bold rounded-md">
                            {{ $menu->purchase_option ?? 'Dine In Only' }}
                        </span>

                        @if($menu->is_featured)
                            <span
                                class="px-2 py-0.5 text-[9px] bg-purple-50 text-purple-800 border border-purple-200 font-bold rounded-md">Featured</span>
                        @endif
                        @if($menu->is_bestseller)
                            <span
                                class="px-2 py-0.5 text-[9px] bg-amber-50 text-amber-800 border border-amber-200 font-bold rounded-md">Best
                                Seller</span>
                        @endif
                        @if($menu->is_recommended)
                            <span
                                class="px-2 py-0.5 text-[9px] bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold rounded-md">Rekomendasi</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-stone-400 text-xs font-semibold">Data menu tidak ditemukan</div>
            @endforelse
        </div>

    </div>

    <!-- Pagination Links -->
    <div class="pt-2">
        {{ $menus->links() }}
    </div>

    {{-- MODAL 1: KELOLA KATEGORI MENU --}}
    @if($isCategoryOpen)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900/40 backdrop-blur-xs p-3 sm:p-4 overflow-hidden">
            <div
                class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-stone-100 transform transition-all my-auto">

                <div class="px-5 py-3.5 border-b border-stone-100 flex items-center justify-between bg-stone-50/60">
                    <div>
                        <h3 class="font-bold text-stone-900 text-sm">Kelola Kategori Menu</h3>
                        <p class="text-[10px] text-stone-500 font-medium">Tambah, ubah, atau hapus kategori menu disini.</p>
                    </div>
                    <button type="button" wire:click="closeCategoryModal"
                        class="p-1 text-stone-400 hover:text-stone-700 rounded-lg hover:bg-stone-100 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-4 sm:p-5 space-y-4">
                    <!-- Form Input/Edit Kategori -->
                    <form wire:submit.prevent="saveCategory" class="space-y-2">
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider">
                            {{ $editingCategoryId ? 'Edit Nama Kategori' : 'Tambah Kategori Baru' }}
                        </label>
                        <div class="flex gap-2">
                            <input type="text" wire:model="categoryName" placeholder="Contoh: Coffee, Dessert..."
                                class="flex-1 border border-stone-200 rounded-xl px-3 py-1.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium">
                            <button type="submit"
                                class="px-3.5 py-1.5 bg-[#8c5a3c] hover:bg-[#73482f] text-white text-xs font-bold rounded-xl transition cursor-pointer shrink-0">
                                {{ $editingCategoryId ? 'Update' : 'Tambah' }}
                            </button>
                            @if($editingCategoryId)
                                <button type="button" wire:click="cancelEditCategory"
                                    class="px-2.5 py-1.5 bg-stone-100 hover:bg-stone-200 text-stone-600 text-xs font-bold rounded-xl transition cursor-pointer">
                                    Batal
                                </button>
                            @endif
                        </div>
                        @error('categoryName') <span
                        class="text-[10px] text-rose-600 block font-medium">{{ $message }}</span> @enderror
                    </form>

                    <!-- List Daftar Kategori -->
                    <div class="space-y-1.5 pt-2 border-t border-stone-100">
                        <span class="block text-[10px] font-bold text-stone-400 uppercase tracking-wider">Daftar Kategori
                            Tersedia</span>

                        <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1 text-xs">
                            @forelse($categories as $cat)
                                <div
                                    class="flex items-center justify-between p-2 rounded-xl bg-stone-50/80 border border-stone-200/60 transition">
                                    <span class="font-bold text-stone-800">{{ $cat->name }}</span>
                                    <div class="flex items-center gap-1">
                                        <button wire:click="editCategory({{ $cat->id }})"
                                            class="p-1 text-stone-500 hover:text-[#8c5a3c] hover:bg-white rounded-lg transition cursor-pointer"
                                            title="Edit Kategori">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="deleteCategory({{ $cat->id }})"
                                            wire:confirm="Hapus kategori '{{ $cat->name }}'?"
                                            class="p-1 text-stone-400 hover:text-rose-600 hover:bg-white rounded-lg transition cursor-pointer"
                                            title="Hapus Kategori">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-[11px] text-stone-400 text-center py-3">Belum ada kategori ditambahkan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="px-5 py-3 border-t border-stone-100 bg-stone-50/50 flex justify-end">
                    <button type="button" wire:click="closeCategoryModal"
                        class="px-3.5 py-1.5 bg-stone-200 hover:bg-stone-300/80 text-stone-700 text-xs font-bold rounded-xl transition cursor-pointer">Tutup</button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL 2: FORM TAMBAH / EDIT MENU --}}
    @if($isOpen)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900/40 backdrop-blur-xs p-3 sm:p-4 overflow-hidden">
            <div
                class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden border border-stone-100 transform transition-all my-auto">

                <div class="px-5 py-3.5 border-b border-stone-100 flex items-center justify-between bg-stone-50/60">
                    <div>
                        <h3 class="font-bold text-stone-900 text-sm">{{ $menuId ? 'Edit Data Menu' : 'Tambah Menu Baru' }}
                        </h3>
                        <p class="text-[10px] text-stone-500 font-medium">Isi informasi detail menu digital di bawah ini.
                        </p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="p-1 text-stone-400 hover:text-stone-700 rounded-lg hover:bg-stone-100 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-4 sm:p-5 space-y-3.5 max-h-[80vh] overflow-y-auto">

                    <!-- Pilihan Kategori & Nama Menu -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Kategori
                                Menu</label>
                            <select wire:model="category"
                                class="w-full border border-stone-200 rounded-xl px-2.5 py-1.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category') <span
                            class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Nama
                                Menu</label>
                            <input type="text" wire:model="name"
                                class="w-full border border-stone-200 rounded-xl px-2.5 py-1.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium"
                                placeholder="Iced Hazelnut Latte">
                            @error('name') <span
                            class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Pilihan Lokasi Outlet -->
                    <div>
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">
                            Ketersediaan Lokasi / Outlet
                        </label>
                        <select wire:model="location"
                            class="w-full border border-stone-200 rounded-xl px-3 py-2 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-semibold bg-stone-50/50">
                            <option value="all">Tersedia di Semua Outlet (Heavenland Park & Pondok Mutiara)</option>
                            <option value="heavenland">Hanya Tersedia di Heavenland Park</option>
                            <option value="pondok_mutiara">Hanya Tersedia di Pondok Mutiara</option>
                        </select>
                        <p class="text-[10px] text-stone-400 font-medium mt-1">Pilih outlet spesifik jika menu ini hanya
                            dibuat eksklusif di cabang tertentu.</p>
                        @error('location') <span
                        class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Harga & Opsi Pembelian (Dine In Only / Take Away / Keduanya) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Harga
                                (Rp)</label>
                            <input type="number" wire:model="price"
                                class="w-full border border-stone-200 rounded-xl px-2.5 py-1.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium"
                                placeholder="28000">
                            @error('price') <span
                            class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Opsi
                                Pembelian</label>
                            <select wire:model="purchase_option"
                                class="w-full border border-stone-200 rounded-xl px-2.5 py-1.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium">
                                <option value="Dine In Only">Dine In Only</option>
                                <option value="Take Away">Take Away Only</option>
                                <option value="Dine In & Take Away">Dine In & Take Away</option>
                            </select>
                            @error('purchase_option') <span
                            class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Deskripsi
                            Singkat</label>
                        <textarea wire:model="description" rows="2"
                            class="w-full border border-stone-200 rounded-xl p-2 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition resize-none font-medium"
                            placeholder="Penjelasan bahan atau cita rasa menu..."></textarea>
                    </div>

                    <!-- Foto Produk -->
                    <div>
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Foto
                            Produk</label>
                        <div class="flex items-center gap-3">
                            <input type="file" wire:model="image" accept="image/*"
                                class="w-full text-[11px] text-stone-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-xl file:border-0 file:text-[11px] file:font-semibold file:bg-amber-50 file:text-[#8c5a3c] hover:file:bg-amber-100 transition cursor-pointer">
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}"
                                    class="w-10 h-10 object-cover rounded-xl border border-stone-200 shadow-2xs shrink-0">
                            @elseif ($oldImage)
                                <img src="{{ asset('storage/' . $oldImage) }}"
                                    class="w-10 h-10 object-cover rounded-xl border border-stone-200 shadow-2xs shrink-0">
                            @endif
                        </div>
                        @error('image') <span
                        class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Checkbox Status -->
                    <div class="flex flex-wrap items-center gap-5 pt-1">
                        <label class="flex items-center gap-2 text-xs font-semibold text-stone-700 cursor-pointer">
                            <input type="checkbox" wire:model="is_featured"
                                class="rounded border-stone-300 text-[#8c5a3c] focus:ring-[#8c5a3c] w-3.5 h-3.5">
                            <span>Highlight Homepage (Featured)</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs font-semibold text-stone-700 cursor-pointer">
                            <input type="checkbox" wire:model="is_bestseller"
                                class="rounded border-stone-300 text-[#8c5a3c] focus:ring-[#8c5a3c] w-3.5 h-3.5">
                            <span>Best Seller</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs font-semibold text-stone-700 cursor-pointer">
                            <input type="checkbox" wire:model="is_recommended"
                                class="rounded border-stone-300 text-[#8c5a3c] focus:ring-[#8c5a3c] w-3.5 h-3.5">
                            <span>Rekomendasi</span>
                        </label>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-stone-100">
                        <button type="button" wire:click="closeModal"
                            class="px-3.5 py-1.5 bg-stone-100 hover:bg-stone-200/80 text-stone-700 text-xs font-bold rounded-xl transition cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-4 py-1.5 bg-[#8c5a3c] hover:bg-[#73482f] text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer">Simpan
                            Data</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>