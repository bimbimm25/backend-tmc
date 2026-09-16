<div x-data="{ 
        modalMerchOpen: @entangle('isOpen'),
        modalCategoryOpen: @entangle('isCategoryOpen')
    }" x-init="
        $watch('modalMerchOpen', value => {
            if (value || modalCategoryOpen) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
        $watch('modalCategoryOpen', value => {
            if (value || modalMerchOpen) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    " class="space-y-4 sm:space-y-6 text-stone-800">

    {{-- Toast Notification --}}
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

    {{-- Filter & Action Bar --}}
    <div
        class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3.5 bg-white p-4 sm:p-5 rounded-2xl border border-stone-200/80 shadow-xs">

        <!-- Search & Filter Area -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full lg:w-auto">
            <!-- Search Bar -->
            <div class="relative w-full sm:w-60">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari merchandise..."
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

            <!-- Select Filter Status Stok -->
            <div class="w-full sm:w-auto">
                <select wire:model.live="filterStatus"
                    class="w-full bg-stone-50/80 border border-stone-200 rounded-xl px-3 py-2 text-xs font-medium text-stone-700 focus:bg-white focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition shadow-2xs cursor-pointer">
                    <option value="">Semua Status Stok</option>
                    <option value="available">Tersedia (Ready)</option>
                    <option value="out_of_stock">Stok Habis</option>
                    <option value="pre_order">Pre-Order</option>
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

            <!-- Button Tambah Merchandise -->
            <button wire:click="openModal"
                class="w-full sm:w-auto px-4 py-2 bg-[#8c5a3c] hover:bg-[#73482f] active:bg-[#5c3a25] text-white text-xs font-bold rounded-xl transition duration-150 shadow-md shadow-[#8c5a3c]/15 flex items-center justify-center gap-2 shrink-0 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Merchandise</span>
            </button>
        </div>

    </div>

    <!-- TAMPILAN MOBILE (CARD LIST) -->
    <div class="block sm:hidden space-y-3">
        @forelse($merchandises as $item)
            <div
                class="bg-white p-3.5 rounded-2xl border border-stone-200/80 shadow-2xs flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}"
                            class="w-12 h-12 object-cover rounded-xl border border-stone-200 shrink-0">
                    @else
                        <div
                            class="w-12 h-12 bg-stone-100 rounded-xl flex items-center justify-center text-[9px] text-stone-400 border border-stone-200 shrink-0 font-semibold">
                            No Image
                        </div>
                    @endif

                    <div class="min-w-0">
                        <div class="font-bold text-stone-900 text-xs truncate flex items-center gap-1.5">
                            <span class="truncate">{{ $item->name }}</span>
                            @if($item->is_featured)
                                <span
                                    class="px-1.5 py-0.2 text-[8px] bg-amber-50 text-amber-800 border border-amber-200 font-bold rounded shrink-0">Featured</span>
                            @endif
                        </div>

                        <div class="text-[11px] font-semibold text-[#8c5a3c] mt-0.5">{{ $item->category }}</div>

                        <div class="font-bold text-stone-800 text-xs mt-0.5">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </div>

                        <div class="flex flex-wrap items-center gap-1 mt-1">
                            <!-- Opsi Pembelian -->
                            <span class="px-1.5 py-0.2 text-[8px] bg-stone-100 text-stone-700 font-semibold rounded">
                                @if($item->purchase_type === 'in_store' || $item->purchase_type === 'In Store')
                                    In Store
                                @elseif($item->purchase_type === 'whatsapp' || $item->purchase_type === 'WhatsApp Order')
                                    WhatsApp
                                @else
                                    Online
                                @endif
                            </span>

                            <!-- Status Stok -->
                            @if($item->stock_status === 'available')
                                <span
                                    class="px-1.5 py-0.2 text-[8px] bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold rounded">Ready</span>
                            @elseif($item->stock_status === 'pre_order')
                                <span
                                    class="px-1.5 py-0.2 text-[8px] bg-amber-50 text-amber-800 border border-amber-200 font-bold rounded">Pre-Order</span>
                            @else
                                <span
                                    class="px-1.5 py-0.2 text-[8px] bg-rose-50 text-rose-700 border border-rose-200 font-bold rounded">Habis</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons Mobile -->
                <div class="flex items-center gap-1 shrink-0">
                    <button wire:click="edit({{ $item->id }})"
                        class="p-2 text-stone-600 hover:text-[#8c5a3c] bg-stone-50 rounded-xl transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <button wire:click="delete({{ $item->id }})"
                        wire:confirm="Yakin ingin menghapus produk {{ $item->name }}?"
                        class="p-2 text-stone-400 hover:text-rose-600 bg-stone-50 rounded-xl transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 text-center rounded-2xl border border-stone-200 text-stone-400 text-xs font-semibold">
                Belum ada merchandise ditambahkan.
            </div>
        @endforelse
    </div>

    <!-- TAMPILAN DESKTOP (TABEL) -->
    <div class="hidden sm:block bg-white rounded-2xl shadow-xs border border-stone-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-stone-50/80 border-b border-stone-200/60 text-[11px] font-bold text-stone-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-16">Foto</th>
                        <th class="py-3.5 px-4">Nama Produk & Kategori</th>
                        <th class="py-3.5 px-4">Harga</th>
                        <th class="py-3.5 px-4">Opsi Pembelian</th>
                        <th class="py-3.5 px-4">Status Stok</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-xs">
                    @forelse($merchandises as $item)
                        <tr class="hover:bg-stone-50/60 transition duration-150">
                            <!-- Thumbnail -->
                            <td class="py-3 px-4">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                        class="w-10 h-10 object-cover rounded-xl border border-stone-200 shadow-2xs">
                                @else
                                    <div
                                        class="w-10 h-10 bg-stone-100 rounded-xl flex items-center justify-center text-[10px] text-stone-400 border border-stone-200/80 font-semibold">
                                        No Image
                                    </div>
                                @endif
                            </td>

                            <!-- Nama Produk & Kategori -->
                            <td class="py-3 px-4">
                                <div class="font-bold text-stone-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span>{{ $item->name }}</span>
                                    @if($item->is_featured)
                                        <span
                                            class="px-2 py-0.5 text-[10px] bg-amber-50 text-amber-800 border border-amber-200/80 font-bold rounded-md">Featured</span>
                                    @endif
                                </div>
                                <div class="text-[11px] font-semibold text-[#8c5a3c] mt-0.5">{{ $item->category }}</div>
                            </td>

                            <!-- Harga -->
                            <td class="py-3 px-4 font-extrabold text-stone-800">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>

                            <!-- Opsi Pembelian -->
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 text-[11px] bg-stone-100 text-stone-700 rounded-lg font-semibold">
                                    @if($item->purchase_type === 'in_store' || $item->purchase_type === 'In Store')
                                        In Store
                                    @elseif($item->purchase_type === 'whatsapp' || $item->purchase_type === 'WhatsApp Order')
                                        WhatsApp Order
                                    @else
                                        Online / Delivery
                                    @endif
                                </span>
                            </td>

                            <!-- Status Stok -->
                            <td class="py-3 px-4">
                                @if($item->stock_status === 'available')
                                    <span
                                        class="px-2 py-0.5 text-[10px] bg-emerald-50 text-emerald-800 border border-emerald-200/80 font-bold rounded-md">Ready
                                        Stock</span>
                                @elseif($item->stock_status === 'pre_order')
                                    <span
                                        class="px-2 py-0.5 text-[10px] bg-amber-50 text-amber-800 border border-amber-200/80 font-bold rounded-md">Pre-Order</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 text-[10px] bg-rose-50 text-rose-700 border border-rose-200/80 font-bold rounded-md">Habis</span>
                                @endif
                            </td>

                            <!-- Action Buttons -->
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="edit({{ $item->id }})"
                                        class="p-1.5 text-stone-600 hover:text-[#8c5a3c] hover:bg-amber-50 rounded-lg transition cursor-pointer"
                                        title="Edit Merchandise">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $item->id }})"
                                        wire:confirm="Yakin ingin menghapus produk {{ $item->name }}?"
                                        class="p-1.5 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer"
                                        title="Hapus Merchandise">
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
                            <td colspan="6" class="py-12 text-center text-stone-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-8 h-8 text-stone-300" fill="none" stroke="currentColor" stroke-width="1.5"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <p class="text-xs font-semibold">Belum ada merchandise ditambahkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Links -->
    <div class="pt-2">
        {{ $merchandises->links() }}
    </div>

    {{-- MODAL 1: KELOLA KATEGORI MERCHANDISE --}}
    @if($isCategoryOpen)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900/40 backdrop-blur-xs p-3 sm:p-4 overflow-hidden">
            <div
                class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-stone-100 transform transition-all my-auto">

                <div class="px-5 py-3.5 border-b border-stone-100 flex items-center justify-between bg-stone-50/60">
                    <div>
                        <h3 class="font-bold text-stone-900 text-sm">Kelola Kategori Merchandise</h3>
                        <p class="text-[10px] text-stone-500 font-medium">Tambah, ubah, atau hapus kategori disini.</p>
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
                            <input type="text" wire:model="categoryName" placeholder="Contoh: Plushie, Keychain..."
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

    {{-- MODAL 2: FORM TAMBAH / EDIT MERCHANDISE --}}
    @if($isOpen)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900/40 backdrop-blur-xs p-3 sm:p-4 overflow-hidden">
            <div
                class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden border border-stone-100 transform transition-all my-auto">

                <div class="px-5 py-3.5 border-b border-stone-100 flex items-center justify-between bg-stone-50/60">
                    <div>
                        <h3 class="font-bold text-stone-900 text-sm">
                            {{ $merchandiseId ? 'Edit Merchandise' : 'Tambah Merchandise Baru' }}
                        </h3>
                        <p class="text-[10px] text-stone-500 font-medium">Isi informasi detail produk merchandise di bawah
                            ini.</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="p-1 text-stone-400 hover:text-stone-700 rounded-lg hover:bg-stone-100 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-4 sm:p-5 space-y-3 max-h-[80vh] overflow-y-auto">

                    <!-- Kategori & Nama Merchandise -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Kategori
                                Merchandise</label>
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
                                Merchandise</label>
                            <input type="text" wire:model="name"
                                class="w-full border border-stone-200 rounded-xl px-2.5 py-1.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium"
                                placeholder="To Meet Plush Bear">
                            @error('name') <span
                            class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Harga & Status Stok -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Harga
                                (Rp)</label>
                            <input type="number" wire:model="price"
                                class="w-full border border-stone-200 rounded-xl px-2.5 py-1.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium"
                                placeholder="189000">
                            @error('price') <span
                            class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Status
                                Stok</label>
                            <select wire:model="stock_status"
                                class="w-full border border-stone-200 rounded-xl px-2.5 py-1.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium">
                                <option value="available">Tersedia (Ready)</option>
                                <option value="pre_order">Pre-Order</option>
                                <option value="out_of_stock">Stok Habis</option>
                            </select>
                            @error('stock_status') <span
                            class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Opsi Pembelian -->
                    <div>
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Opsi
                            Pembelian</label>
                        <select wire:model="purchase_type"
                            class="w-full border border-stone-200 rounded-xl px-2.5 py-1.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition font-medium">
                            <option value="in_store">In Store Only (Ditempat)</option>
                            <option value="whatsapp">WhatsApp Order</option>
                            <option value="online">Online / Delivery</option>
                        </select>
                        @error('purchase_type') <span
                        class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Deskripsi Produk -->
                    <div>
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Deskripsi
                            Singkat</label>
                        <textarea wire:model="description" rows="2"
                            class="w-full border border-stone-200 rounded-xl p-2 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition resize-none font-medium"
                            placeholder="Penjelasan bahan atau spesifikasi produk..."></textarea>
                    </div>

                    <!-- Upload Foto -->
                    <div>
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Foto
                            Merchandise</label>
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

                    <!-- Highlight Featured Checkbox -->
                    <div class="pt-1">
                        <label class="flex items-center gap-2 text-xs font-semibold text-stone-700 cursor-pointer">
                            <input type="checkbox" wire:model="is_featured"
                                class="rounded border-stone-300 text-[#8c5a3c] focus:ring-[#8c5a3c] w-3.5 h-3.5">
                            <span>Tampilkan di Highlight Homepage (Featured)</span>
                        </label>
                    </div>

                    <!-- Form Footer Buttons -->
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