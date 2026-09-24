<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

class BannerManager extends Component
{
    use WithFileUploads;

    /**
     * @var Collection<int, Banner>
     */
    public $banners;

    public bool $isOpen = false;
    public ?int $bannerId = null;
    public string $page_key = 'home';
    public string $title = '';
    public string $subtitle = '';

    /**
     * @var mixed
     */
    public $image = null;

    public ?string $oldImage = null;
    public bool $is_active = true;
    public string $successMessage = '';
    public string $selectedFilter = 'all';

    // Daftar semua target halaman
    public array $pageOptions = [
        'home' => 'Homepage Utama',
        'about' => 'About Us (Tentang Kami)',
        'menu' => 'Digital Menu',
        'merchandise' => 'Merchandise Shop',
        'event' => 'Event & Workshop',
        'birthday' => 'Birthday & Private Event',
        'roblox' => 'Roblox Gaming World',
        'visit-us' => 'Visit Us (Halaman Utama)',
        'heavenland-park' => 'Visit Us - Heavenland Park',
        'pondok-mutiara' => 'Visit Us - Pondok Mutiara',
        'blog' => 'Blog & Artikel',
        'career' => 'Career / Lowongan Kerja',
        'faq' => 'FAQ (Tanya Jawab)',
    ];

    protected function rules(): array
    {
        return [
            'page_key' => 'required|string',
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
        ];
    }

    protected function messages(): array
    {
        return [
            'page_key.required' => 'Mohon tentukan halaman target untuk penempatan banner.',
            'image.file' => 'Berkas yang diunggah tidak valid.',
            'image.mimes' => 'Format berkas tidak didukung. Mohon gunakan berkas berekstensi PNG, JPG, JPEG, atau WebP.',
            'image.max' => 'Ukuran berkas melebihi kapasitas yang diizinkan (maksimal 5 MB). Silakan gunakan gambar dengan resolusi yang telah dikompresi.',
        ];
    }

    public function mount(): void
    {
        $this->loadBanners();
    }

    public function loadBanners(): void
    {
        $query = Banner::query();
        if ($this->selectedFilter !== 'all') {
            $query->where('page_key', $this->selectedFilter);
        }
        $this->banners = $query->latest()->get();
    }

    public function updatedSelectedFilter(): void
    {
        $this->loadBanners();
    }

    public function updatedImage(): void
    {
        $this->validateOnly('image');
    }

    public function openModal(?int $id = null): void
    {
        $this->resetForm();
        if ($id) {
            $banner = Banner::findOrFail($id);
            $this->bannerId = (int) $banner->id;
            $this->page_key = (string) $banner->page_key;
            $this->title = (string) ($banner->title ?? '');
            $this->subtitle = (string) ($banner->subtitle ?? '');
            $this->oldImage = $banner->image;
            $this->is_active = (bool) $banner->is_active;
        }
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->bannerId = null;
        $this->page_key = 'home';
        $this->title = '';
        $this->subtitle = '';
        $this->image = null;
        $this->oldImage = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate();

        $imagePath = $this->oldImage;
        if ($this->image) {
            if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                Storage::disk('public')->delete($this->oldImage);
            }
            $imagePath = $this->image->store('banners', 'public');
        }

        Banner::updateOrCreate(
            ['page_key' => $this->page_key],
            [
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'image' => $imagePath,
                'is_active' => $this->is_active,
            ]
        );

        $this->successMessage = 'Konfigurasi banner berhasil diperbarui.';
        $this->loadBanners();
        $this->closeModal();
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.banner-manager');
    }
}