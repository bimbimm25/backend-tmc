<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Career;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Storage;

class CareerManager extends Component
{
    use WithPagination;

    // Filter & Search State
    public string $search = '';
    public string $activeTab = 'positions'; // positions | applicants

    // Modal State Lowongan
    public bool $isOpen = false;
    public ?int $careerId = null;

    // Form Fields Lowongan
    public string $title = '';
    public string $department = 'Barista';
    public string $type = 'Full-time';
    public string $location_name = 'Heaveland Park';
    public string $description = '';
    public string $requirements = '';
    public string $benefits = '';
    public bool $is_active = true;

    // Flash Message
    public string $successMessage = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->careerId = null;
        $this->title = '';
        $this->department = 'Barista';
        $this->type = 'Full-time';
        $this->location_name = 'Heaveland Park';
        $this->description = '';
        $this->requirements = '';
        $this->benefits = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:100',
            'type' => 'required|in:Full-time,Part-time,Contract,Internship',
            'location_name' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Career::updateOrCreate(
            ['id' => $this->careerId],
            [
                'title' => $this->title,
                'department' => $this->department,
                'type' => $this->type,
                'location_name' => $this->location_name,
                'description' => $this->description,
                'requirements' => $this->requirements,
                'benefits' => $this->benefits,
                'is_active' => $this->is_active,
            ]
        );

        $this->successMessage = $this->careerId ? 'Lowongan berhasil diperbarui!' : 'Lowongan baru berhasil dibuka!';
        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $career = Career::findOrFail($id);
        $this->careerId = $career->id;
        $this->title = $career->title;
        $this->department = $career->department;
        $this->type = $career->type;
        $this->location_name = $career->location_name;
        $this->description = $career->description;
        $this->requirements = $career->requirements ?? '';
        $this->benefits = $career->benefits ?? '';
        $this->is_active = (bool) $career->is_active;

        $this->isOpen = true;
    }

    public function deleteCareer(int $id): void
    {
        Career::findOrFail($id)->delete();
        $this->successMessage = 'Lowongan berhasil dihapus!';
    }

    public function deleteApplicant(int $id): void
    {
        $app = JobApplication::findOrFail($id);
        if ($app->resume_path) {
            Storage::disk('public')->delete($app->resume_path);
        }
        $app->delete();

        $this->successMessage = 'Data pelamar berhasil dihapus!';
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.career-manager', [
            'careers' => Career::withCount('applications')
                ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
                ->latest()->paginate(8, ['*'], 'careersPage'),
            'applicants' => JobApplication::with('career')
                ->when($this->search, fn($q) => $q->where('full_name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%'))
                ->latest()->paginate(10, ['*'], 'applicantsPage')
        ]);
    }
}