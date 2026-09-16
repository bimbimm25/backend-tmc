<div x-data="{ 
        modalRobloxOpen: @entangle('isOpen') 
    }" x-init="
        $watch('modalRobloxOpen', value => {
            if (value) {
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

    {{-- Header Action & Filter Bar --}}
    <div
        class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3.5 bg-white p-4 sm:p-5 rounded-3xl border border-stone-200/80 shadow-xs">

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full sm:w-auto">
            <!-- Search Bar -->
            <div class="relative w-full sm:w-64">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari badge / reward..."
                    class="w-full bg-stone-50/80 border border-stone-200 rounded-xl pl-9 pr-4 py-2 text-xs text-stone-800 focus:bg-white focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition shadow-2xs font-medium placeholder:text-stone-400">
                <svg class="w-4 h-4 text-stone-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Filter Kategori / Role -->
            <div class="w-full sm:w-auto">
                <select wire:model.live="filterCategory"
                    class="w-full bg-stone-50/80 border border-stone-200 rounded-xl px-3 py-2 text-xs font-medium text-stone-700 focus:bg-white focus:outline-none focus:border-[#8c5a3c] focus:ring-1 focus:ring-[#8c5a3c] transition shadow-2xs cursor-pointer">
                    <option value="">Semua Role / Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Tombol Tambah Badge -->
        <button wire:click="openModal"
            class="px-4 py-2 bg-[#8c5a3c] hover:bg-[#73482f] active:bg-[#5c3a25] text-white text-xs font-bold rounded-xl transition duration-150 shadow-md shadow-[#8c5a3c]/15 flex items-center justify-center gap-2 w-full sm:w-auto cursor-pointer shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Badge & Misi Baru</span>
        </button>
    </div>

    <!-- TAMPILAN MOBILE (CARD LIST) -->
    <div class="block sm:hidden space-y-3">
        @forelse($missions as $item)
            <div
                class="bg-white p-3.5 rounded-2xl border border-stone-200/80 shadow-2xs flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}"
                            class="w-12 h-12 object-contain rounded-xl border border-stone-200 p-1 shrink-0 bg-[#faf6f0]">
                    @else
                        <div
                            class="w-12 h-12 bg-stone-100 rounded-xl flex items-center justify-center text-[9px] text-stone-400 border border-stone-200 shrink-0 font-semibold">
                            No Badge
                        </div>
                    @endif

                    <div class="min-w-0 space-y-0.5">
                        <div class="font-bold text-stone-900 text-xs truncate">{{ $item->badge_name ?: $item->title }}</div>
                        <div class="text-[10px] font-bold text-[#8c5a3c]">{{ $item->category }}</div>
                        <div class="text-[10px] text-stone-500 truncate">Syarat: {{ $item->requirement }}</div>
                        <div class="text-[10px] text-emerald-700 font-bold truncate">Reward: {{ $item->reward_title }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-1 shrink-0">
                    <button wire:click="openModal({{ $item->id }})"
                        class="p-2 text-stone-600 hover:text-[#8c5a3c] bg-stone-50 rounded-xl transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <button wire:click="delete({{ $item->id }})" wire:confirm="Hapus badge ini?"
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
                Belum ada badge & misi ditambahkan.
            </div>
        @endforelse
    </div>

    <!-- TAMPILAN DESKTOP (TABEL) -->
    <div class="hidden sm:block bg-white rounded-3xl shadow-xs border border-stone-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-stone-50/80 border-b border-stone-200/60 text-[11px] font-bold text-stone-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-16">Badge</th>
                        <th class="py-3.5 px-4">Role / Kategori</th>
                        <th class="py-3.5 px-4">Nama Badge / Misi</th>
                        <th class="py-3.5 px-4">Syarat Pencapaian Game</th>
                        <th class="py-3.5 px-4">Reward di Cafe</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-xs">
                    @forelse($missions as $item)
                        <tr class="hover:bg-stone-50/60 transition duration-150">
                            <td class="py-3 px-4">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                        class="w-11 h-11 object-contain rounded-xl border border-stone-200 p-1 bg-[#faf6f0] shadow-2xs">
                                @else
                                    <div
                                        class="w-11 h-11 bg-stone-100 rounded-xl flex items-center justify-center text-[9px] text-stone-400 border border-stone-200 font-semibold">
                                        No Badge
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span
                                    class="px-2.5 py-1 text-[10px] font-bold bg-[#f4ece1] text-[#8c5a3c] rounded-lg border border-[#e6ccb2]/60">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-bold text-stone-900 text-sm">
                                {{ $item->badge_name ?: $item->title }}
                            </td>
                            <td class="py-3 px-4 text-stone-700 font-medium max-w-xs">
                                {{ $item->requirement }}
                            </td>
                            <td class="py-3 px-4 font-bold text-emerald-700">
                                {{ $item->reward_title }}
                            </td>
                            <td class="py-3 px-4">
                                @if($item->is_active)
                                    <span
                                        class="px-2 py-0.5 text-[10px] bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold rounded-md">Aktif</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 text-[10px] bg-stone-100 text-stone-600 rounded-md font-medium">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="openModal({{ $item->id }})"
                                        class="p-1.5 text-stone-600 hover:text-[#8c5a3c] hover:bg-amber-50 rounded-lg transition cursor-pointer"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $item->id }})" wire:confirm="Hapus badge ini?"
                                        class="p-1.5 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer"
                                        title="Hapus">
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
                                            d="M12 8v13m0-13V4a2 2 0 112 2h-2zm0 0V4a2 2 0 10-2 2h2m-7 8h14a2 2 0 012 2v5H3v-5a2 2 0 012-2z" />
                                    </svg>
                                    <p class="text-xs font-semibold">Belum ada badge & misi ditambahkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Modern & Clean -->
    <div
        class="bg-white p-3.5 rounded-2xl border border-stone-200/80 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-[11px] font-semibold text-stone-500">
            Menampilkan <span class="font-bold text-stone-900">{{ $missions->firstItem() ?? 0 }}</span> - <span
                class="font-bold text-stone-900">{{ $missions->lastItem() ?? 0 }}</span> dari <span
                class="font-bold text-[#8c5a3c]">{{ $missions->total() }}</span> badge & misi
        </div>

        @if($missions->hasPages())
            <div class="flex items-center gap-1.5">
                {{-- Previous Page Link --}}
                @if($missions->onFirstPage())
                    <span
                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-stone-100 text-stone-300 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                @else
                    <button wire:click="previousPage"
                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-stone-50 hover:bg-[#8c5a3c] text-stone-600 hover:text-white border border-stone-200 transition cursor-pointer shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                @endif

                {{-- Page Numbers --}}
                @foreach($missions->getUrlRange(max(1, $missions->currentPage() - 2), min($missions->lastPage(), $missions->currentPage() + 2)) as $page => $url)
                    @if($page == $missions->currentPage())
                        <span
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-[#8c5a3c] text-white text-xs font-black shadow-xs">
                            {{ $page }}
                        </span>
                    @else
                        <button wire:click="gotoPage({{ $page }})"
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-stone-50 hover:bg-amber-100 text-stone-700 text-xs font-bold border border-stone-200 transition cursor-pointer">
                            {{ $page }}
                        </button>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if($missions->hasMorePages())
                    <button wire:click="nextPage"
                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-stone-50 hover:bg-[#8c5a3c] text-stone-600 hover:text-white border border-stone-200 transition cursor-pointer shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @else
                    <span
                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-stone-100 text-stone-300 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </div>
        @endif
    </div>

    {{-- MODAL TAMBAH / EDIT BADGE --}}
    @if($isOpen)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900/40 backdrop-blur-xs p-3 sm:p-4 overflow-hidden">
            <div
                class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden border border-stone-100 transform transition-all my-auto">

                <div class="px-5 py-4 border-b border-stone-100 flex items-center justify-between bg-stone-50/70">
                    <div>
                        <h3 class="font-bold text-stone-900 text-sm">
                            {{ $missionId ? 'Edit Badge & Misi' : 'Tambah Badge & Misi Baru' }}
                        </h3>
                        <p class="text-[10.5px] text-stone-500 font-medium">Atur role, syarat pencapaian game, dan hadiah
                            reward di cafe.</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="p-1.5 text-stone-400 hover:text-stone-700 rounded-xl hover:bg-stone-100 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-5 space-y-4 max-h-[80vh] overflow-y-auto">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Role /
                                Kategori Misi</label>
                            <input type="text" wire:model="category"
                                class="w-full border border-stone-200 rounded-xl px-3 py-2 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] transition font-medium"
                                placeholder="Contoh: Chef Career, Cashier, Obby">
                            @error('category') <span
                            class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Nama
                                Badge</label>
                            <input type="text" wire:model="badge_name"
                                class="w-full border border-stone-200 rounded-xl px-3 py-2 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] transition font-medium"
                                placeholder="Contoh: Bear Obby Rookie">
                            @error('badge_name') <span
                            class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Syarat
                                Pencapaian Game</label>
                            <input type="text" wire:model="requirement"
                                class="w-full border border-stone-200 rounded-xl px-3 py-2 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] transition font-medium"
                                placeholder="Contoh: Finish Obby 1x">
                            @error('requirement') <span
                            class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Reward
                                Hadiah di Cafe</label>
                            <input type="text" wire:model="reward_title"
                                class="w-full border border-stone-200 rounded-xl px-3 py-2 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] transition font-medium"
                                placeholder="Contoh: Free Stiker / Diskon 20%">
                            @error('reward_title') <span
                            class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Upload Foto
                            Emblem Badge (PNG Transparan)</label>
                        <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-2xl border border-stone-200">
                            <input type="file" wire:model="image" accept="image/*"
                                class="w-full text-xs text-stone-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-[#8c5a3c] hover:file:bg-amber-100 transition cursor-pointer">

                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}"
                                    class="w-10 h-10 object-contain rounded-xl border border-stone-200 p-1 bg-white shadow-2xs shrink-0">
                            @elseif ($oldImage)
                                <img src="{{ asset('storage/' . $oldImage) }}"
                                    class="w-10 h-10 object-contain rounded-xl border border-stone-200 p-1 bg-white shadow-2xs shrink-0">
                            @endif
                        </div>
                        @error('image') <span
                        class="text-[10px] text-rose-600 mt-0.5 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-1">
                        <label class="flex items-center gap-2 text-xs font-semibold text-stone-700 cursor-pointer">
                            <input type="checkbox" wire:model="is_active"
                                class="rounded border-stone-300 text-[#8c5a3c] focus:ring-[#8c5a3c] w-3.5 h-3.5">
                            <span>Aktifkan Badge & Misi Ini</span>
                        </label>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-stone-100">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-2xl transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2 bg-[#8c5a3c] hover:bg-[#73482f] text-white text-xs font-bold rounded-2xl shadow-md transition cursor-pointer">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>