<div x-data="{ 
        modalOpen: @entangle('isOpen'),
        uploadError: '',
        isUploading: false,
        uploadTimeout: null,

        startUploadWatch(event) {
            this.uploadError = '';
            const file = event.target.files[0];
            if (!file) return;

            const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
            const maxSize = 3 * 1024 * 1024; // 3 MB

            if (!allowedTypes.includes(file.type)) {
                this.uploadError = 'Format gambar tidak didukung. Mohon gunakan file berformat PNG, JPG, atau WebP.';
                event.target.value = '';
                return;
            }

            if (file.size > maxSize) {
                this.uploadError = 'Ukuran gambar terlalu besar (lebih dari 3 MB). Silakan gunakan gambar yang lebih kecil atau kompresi terlebih dahulu.';
                event.target.value = '';
                return;
            }

            // Mulai hitung mundur batas waktu (15 detik) untuk mendeteksi server/koneksi macet
            this.isUploading = true;
            clearTimeout(this.uploadTimeout);
            this.uploadTimeout = setTimeout(() => {
                if (this.isUploading) {
                    this.isUploading = false;
                    this.uploadError = 'Server sedang sibuk atau mengalami gangguan saat menerima gambar. Silakan tunggu beberapa saat lalu coba unggah ulang.';
                    if ($refs.imageInput) $refs.imageInput.value = '';
                }
            }, 15000);
        },

        handleUploadFinish() {
            this.isUploading = false;
            clearTimeout(this.uploadTimeout);
        },

        handleUploadError() {
            this.isUploading = false;
            clearTimeout(this.uploadTimeout);
            this.uploadError = 'Gagal terhubung ke server. Berkas gambar mungkin melebihi kapasitas maksimal yang diizinkan oleh sistem server, atau koneksi Anda terputus. Silakan coba lagi.';
            if ($refs.imageInput) $refs.imageInput.value = '';
        }
    }" 
    x-init="
        $watch('modalOpen', value => {
            if (value) {
                document.body.style.overflow = 'hidden';
                uploadError = '';
                isUploading = false;
                clearTimeout(uploadTimeout);
            } else {
                document.body.style.overflow = '';
                clearTimeout(uploadTimeout);
            }
        });
    " 
    x-on:livewire-upload-finish="handleUploadFinish()"
    x-on:livewire-upload-error="handleUploadError()"
    class="space-y-6 text-stone-800">

    {{-- Flash Toast Notification Sukses --}}
    @if($successMessage)
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="font-semibold">{{ $successMessage }}</span>
            </div>
            <button wire:click="$set('successMessage', '')" class="text-emerald-500 hover:text-emerald-800 p-1 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Header Action & Filter Bar --}}
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 bg-white p-5 rounded-3xl border border-stone-200/80 shadow-xs">
        <div>
            <h2 class="text-base sm:text-lg font-black text-stone-900">Kelola Banner Tiap Halaman</h2>
            <p class="text-xs text-stone-500 font-medium">Ubah foto hero banner dan teks tiap page. Mendukung tag &lt;br&gt; untuk enter/baris baru.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <!-- Filter Page -->
            <select wire:model.live="selectedFilter"
                class="bg-stone-50 border border-stone-200 rounded-xl px-3 py-2 text-xs text-stone-700 focus:outline-none focus:border-[#8c5a3c] font-semibold cursor-pointer">
                <option value="all">Semua Halaman ({{ count($banners) }})</option>
                @foreach($pageOptions as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>

            <button wire:click="openModal()"
                class="px-4 py-2 bg-[#8c5a3c] hover:bg-[#73482f] active:bg-[#5c3a25] text-white text-xs font-bold rounded-xl transition flex items-center gap-2 shadow-xs cursor-pointer shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah / Edit Banner</span>
            </button>
        </div>
    </div>

    {{-- Cards Grid Daftar Banner Tiap Page --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
        @forelse($banners as $item)
            <div class="bg-white rounded-3xl border border-stone-200/80 shadow-xs p-4 space-y-3 flex flex-col justify-between hover:border-[#8c5a3c] transition duration-200">
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 bg-amber-50 text-[#8c5a3c] border border-amber-200/60 rounded-lg text-[10px] font-black uppercase tracking-wider">
                            {{ $pageOptions[$item->page_key] ?? $item->page_key }}
                        </span>
                        <span class="text-[10px] font-bold {{ $item->is_active ? 'text-emerald-600' : 'text-stone-400' }}">
                            {{ $item->is_active ? '● Aktif' : '○ Nonaktif' }}
                        </span>
                    </div>

                    {{-- Preview Banner --}}
                    <div class="w-full aspect-video bg-stone-100 rounded-2xl overflow-hidden border border-stone-200 flex items-center justify-center relative">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->page_key }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-[11px] text-stone-400 font-semibold">Belum ada gambar banner</span>
                        @endif
                    </div>

                    <div class="space-y-1">
                        <h4 class="font-bold text-stone-900 text-xs sm:text-sm line-clamp-2 leading-tight">
                            @if($item->title)
                                {!! nl2br($item->title) !!}
                            @else
                                <span class="text-stone-400 font-normal italic">(Tanpa Judul Banner)</span>
                            @endif
                        </h4>
                        <p class="text-[11px] text-stone-500 line-clamp-2 leading-relaxed">
                            @if($item->subtitle)
                                {!! nl2br($item->subtitle) !!}
                            @else
                                Foto background aktif
                            @endif
                        </p>
                    </div>
                </div>

                <div class="pt-2.5 border-t border-stone-100 flex items-center justify-between">
                    <span class="text-[10px] text-stone-400 font-medium">Diperbarui: {{ $item->updated_at->format('d M Y') }}</span>
                    <button wire:click="openModal({{ $item->id }})"
                        class="px-3 py-1.5 bg-stone-100 hover:bg-[#8c5a3c] hover:text-white text-stone-700 text-xs font-bold rounded-xl transition cursor-pointer">
                        Edit Banner
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-stone-400 bg-white rounded-3xl border border-stone-200">
                <p class="text-xs font-semibold">Belum ada banner yang ditambahkan untuk filter ini.</p>
            </div>
        @endforelse
    </div>

    {{-- MODAL TAMBAH/EDIT BANNER --}}
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900/40 backdrop-blur-xs p-3 sm:p-4 overflow-hidden">
            <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden border border-stone-100 transform transition-all my-auto">
                <div class="px-5 py-4 border-b border-stone-100 flex items-center justify-between bg-stone-50/70">
                    <div>
                        <h3 class="font-bold text-stone-900 text-sm">
                            {{ $bannerId ? 'Edit Banner Halaman' : 'Tambah Banner Halaman' }}
                        </h3>
                        <p class="text-[10px] text-stone-500 font-medium">Gunakan tag <code>&lt;br&gt;</code> atau tombol Enter untuk membuat baris baru.</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="p-1.5 text-stone-400 hover:text-stone-700 rounded-xl hover:bg-stone-100 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-5 space-y-4 max-h-[80vh] overflow-y-auto">
                    
                    {{-- Alert Peringatan Error Profesional & Awam --}}
                    <div x-show="uploadError" x-cloak
                        class="p-3.5 bg-rose-50 border border-rose-200/80 rounded-2xl flex items-start gap-3 text-rose-800 text-xs shadow-2xs">
                        <div class="w-5 h-5 rounded-lg bg-rose-500/10 text-rose-600 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                <path stroke-linecap="round" d="M12 8v4m0 4h.01"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <span class="font-bold block">Gagal Mengunggah Gambar</span>
                            <span class="text-[11px] leading-relaxed text-rose-700" x-text="uploadError"></span>
                        </div>
                        <button type="button" @click="uploadError = ''" class="text-rose-400 hover:text-rose-700 p-0.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Target Halaman Website --}}
                    <div>
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Target Halaman Website</label>
                        <select wire:model="page_key"
                            class="w-full border border-stone-200 rounded-2xl px-3.5 py-2.5 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] transition font-semibold cursor-pointer">
                            @foreach($pageOptions as $key => $label)
                                <option value="{{ $key }}">{{ $label }} ({{ $key }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Judul Banner --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider">Judul Banner</label>
                            <span class="text-[10px] text-[#8c5a3c] font-bold">Mendukung &lt;br&gt; / Enter</span>
                        </div>
                        <textarea wire:model="title" rows="2" placeholder="Contoh: TO MEET<br>Universe"
                            class="w-full border border-stone-200 rounded-2xl p-3 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] transition resize-none font-medium"></textarea>
                    </div>

                    {{-- Deskripsi / Subtitle --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider">Deskripsi / Subtitle</label>
                            <span class="text-[10px] text-[#8c5a3c] font-bold">Mendukung &lt;br&gt; / Enter</span>
                        </div>
                        <textarea wire:model="subtitle" rows="3" placeholder="Contoh: A cozy cafe,<br>a world of friends..."
                            class="w-full border border-stone-200 rounded-2xl p-3 text-xs text-stone-800 focus:outline-none focus:border-[#8c5a3c] transition resize-none font-medium"></textarea>
                    </div>

                    {{-- Upload Gambar Banner --}}
                    <div>
                        <label class="block text-[10px] font-bold text-stone-800 uppercase tracking-wider mb-1">Upload Gambar Banner Rasio 16:9 (Maks. 3 MB)</label>
                        <div class="space-y-2">
                            <input type="file" 
                                x-ref="imageInput"
                                wire:model="image" 
                                accept="image/png,image/jpeg,image/jpg,image/webp"
                                @change="startUploadWatch($event)"
                                class="w-full text-xs text-stone-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-[#8c5a3c] hover:file:bg-amber-100 transition cursor-pointer">

                            {{-- Indikator Loading + Tombol Batalkan --}}
                            <div x-show="isUploading" x-cloak class="w-full p-2.5 bg-amber-50 border border-amber-200/80 rounded-xl flex items-center justify-between text-amber-800 text-[11px] font-medium animate-pulse">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin text-[#8c5a3c]" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span>Sedang mengunggah gambar ke server, mohon tunggu...</span>
                                </div>
                                <button type="button" @click="handleUploadError()" class="text-amber-700 hover:text-amber-900 underline text-[10px] font-bold cursor-pointer">
                                    Batalkan
                                </button>
                            </div>

                            @if ($image)
                                <div class="w-full aspect-video rounded-2xl overflow-hidden border border-stone-200 shadow-2xs">
                                    <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                                </div>
                            @elseif ($oldImage)
                                <div class="w-full aspect-video rounded-2xl overflow-hidden border border-stone-200 shadow-2xs">
                                    <img src="{{ asset('storage/' . $oldImage) }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                        </div>

                        {{-- Pesan Kesalahan Backend Livewire --}}
                        @error('image')
                            <div class="p-2.5 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-2 text-rose-700 text-[11px] font-medium mt-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Checkbox Status Aktif --}}
                    <div class="flex items-center gap-2 pt-1">
                        <label class="flex items-center gap-2 text-xs font-semibold text-stone-700 cursor-pointer">
                            <input type="checkbox" wire:model="is_active"
                                class="rounded-md border-stone-300 text-[#8c5a3c] focus:ring-[#8c5a3c] w-4 h-4">
                            <span>Aktifkan Banner Ini</span>
                        </label>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="pt-3 flex justify-end gap-2 border-t border-stone-100">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-2xl transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit"
                            wire:loading.attr="disabled"
                            wire:target="image, save"
                            :disabled="isUploading"
                            class="px-5 py-2 bg-[#8c5a3c] hover:bg-[#73482f] text-white text-xs font-bold rounded-2xl shadow-xs transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1.5">
                            <span wire:loading.remove wire:target="save">Simpan Banner</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>