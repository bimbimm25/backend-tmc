<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\RobloxMission;
use Illuminate\Support\Facades\Storage;

class RobloxManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterCategory = '';

    // Form Modal State
    public $isOpen = false;
    public $missionId = null;
    public $category = '';
    public $badge_name = '';
    public $requirement = '';
    public $reward_title = '';
    public $image;
    public $oldImage;
    public $is_active = true;

    public $successMessage = '';

    protected $rules = [
        'category' => 'required|string|max:255',
        'badge_name' => 'required|string|max:255',
        'requirement' => 'required|string|max:255',
        'reward_title' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5048',
        'is_active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->resetForm();

        if ($id) {
            $mission = RobloxMission::findOrFail($id);
            $this->missionId = $mission->id;
            $this->category = $mission->category ?? '';
            $this->badge_name = $mission->badge_name ?? $mission->title ?? '';
            $this->requirement = $mission->requirement ?? $mission->description ?? '';
            $this->reward_title = $mission->reward_title ?? '';
            $this->oldImage = $mission->image;
            $this->is_active = (bool) $mission->is_active;
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
        $this->missionId = null;
        $this->category = '';
        $this->badge_name = '';
        $this->requirement = '';
        $this->reward_title = '';
        $this->image = null;
        $this->oldImage = null;
        $this->is_active = true;
    }

    public function save()
    {
        $this->validate();

        $imagePath = $this->oldImage;
        if ($this->image) {
            if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                Storage::disk('public')->delete($this->oldImage);
            }
            $imagePath = $this->image->store('roblox', 'public');
        }

        RobloxMission::updateOrCreate(
            ['id' => $this->missionId],
            [
                'category' => $this->category,
                'title' => $this->badge_name,
                'badge_name' => $this->badge_name,
                'requirement' => $this->requirement,
                'description' => $this->requirement,
                'reward_title' => $this->reward_title,
                'image' => $imagePath,
                'is_active' => $this->is_active,
            ]
        );

        $this->successMessage = $this->missionId ? 'Badge & Misi berhasil diperbarui!' : 'Badge & Misi baru berhasil ditambahkan!';
        $this->closeModal();
    }

    public function delete($id)
    {
        $mission = RobloxMission::findOrFail($id);
        if ($mission->image && Storage::disk('public')->exists($mission->image)) {
            Storage::disk('public')->delete($mission->image);
        }
        $mission->delete();
        $this->successMessage = 'Badge berhasil dihapus!';
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $missions = RobloxMission::query()
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('badge_name', 'like', "%{$this->search}%")
                        ->orWhere('title', 'like', "%{$this->search}%")
                        ->orWhere('requirement', 'like', "%{$this->search}%")
                        ->orWhere('reward_title', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterCategory, function ($q) {
                $q->where('category', $this->filterCategory);
            })
            ->latest()
            ->paginate(10);

        $categories = RobloxMission::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category');

        return view('livewire.admin.roblox-manager', [
            'missions' => $missions,
            'categories' => $categories,
        ]);
    }
}