<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerManager extends Component
{
    use WithFileUploads;

    public $banners;
    public $isOpen = false;
    public $bannerId = null;
    public $page_key = 'home';
    public $title = '';
    public $subtitle = '';
    public $image;
    public $oldImage;
    public $is_active = true;
    public $successMessage = '';
    public $selectedFilter = 'all';

    // Daftar semua target halaman
    public $pageOptions = [
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

    public function mount()
    {
        $this->loadBanners();
    }

    public function loadBanners()
    {
        $query = Banner::query();
        if ($this->selectedFilter !== 'all') {
            $query->where('page_key', $this->selectedFilter);
        }
        $this->banners = $query->latest()->get();
    }

    public function updatedSelectedFilter()
    {
        $this->loadBanners();
    }

    public function openModal($id = null)
    {
        $this->resetForm();
        if ($id) {
            $banner = Banner::findOrFail($id);
            $this->bannerId = $banner->id;
            $this->page_key = $banner->page_key;
            $this->title = $banner->title;
            $this->subtitle = $banner->subtitle;
            $this->oldImage = $banner->image;
            $this->is_active = (bool) $banner->is_active;
        }
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    private function resetForm()
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

    public function save()
    {
        $this->validate([
            'page_key' => 'required|string',
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

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

        $this->successMessage = 'Banner berhasil disimpan!';
        $this->loadBanners();
        $this->closeModal();
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.banner-manager');
    }
}