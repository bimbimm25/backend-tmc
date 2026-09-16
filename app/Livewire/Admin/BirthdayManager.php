<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BirthdayManager extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterLocation = '';

    public bool $isOpen = false;
    public ?int $birthdayId = null;

    public string $title = '';
    public string $location_name = 'Semua Lokasi';
    public string $price = '';
    public ?string $capacity = null;
    public string $description = '';
    public mixed $image = null;
    public ?string $oldImage = null;
    public bool $is_active = true;

    public string $successMessage = '';

    protected string $paginationTheme = 'tailwind';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterLocation(): void { $this->resetPage(); }

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
        $this->birthdayId = null;
        $this->title = '';
        $this->location_name = 'Semua Lokasi';
        $this->price = '';
        $this->capacity = null;
        $this->description = '';
        $this->image = null;
        $this->oldImage = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'location_name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'capacity' => 'nullable|numeric|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $imagePath = $this->oldImage;

        if ($this->image) {
            if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                Storage::disk('public')->delete($this->oldImage);
            }
            $imagePath = $this->image->store('birthdays', 'public');
        }

        Event::updateOrCreate(
            ['id' => $this->birthdayId],
            [
                'type' => 'birthday_package',
                'category' => 'Birthday & Private',
                'title' => $this->title,
                'slug' => Str::slug($this->title),
                'location_name' => $this->location_name,
                'price' => $this->price,
                'capacity' => $this->capacity ?: null,
                'event_date' => null,
                'description' => $this->description,
                'image' => $imagePath,
                'is_active' => $this->is_active,
            ]
        );

        $this->successMessage = $this->birthdayId ? 'Paket Birthday berhasil diperbarui!' : 'Paket Birthday baru berhasil ditambahkan!';
        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $birthday = Event::findOrFail($id);
        $this->birthdayId = $birthday->id;
        $this->title = $birthday->title;
        $this->location_name = $birthday->location_name ?? 'Semua Lokasi';
        $this->price = (string) $birthday->price;
        $this->capacity = $birthday->capacity ? (string) $birthday->capacity : null;
        $this->description = $birthday->description ?? '';
        $this->oldImage = $birthday->image;
        $this->is_active = (bool) $birthday->is_active;

        $this->isOpen = true;
    }

    public function delete(int $id): void
    {
        $birthday = Event::findOrFail($id);
        if ($birthday->image && Storage::disk('public')->exists($birthday->image)) {
            Storage::disk('public')->delete($birthday->image);
        }
        $birthday->delete();

        $this->successMessage = 'Paket Birthday berhasil dihapus!';
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $query = Event::where('type', 'birthday_package')->latest();

        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->filterLocation)) {
            $query->where('location_name', $this->filterLocation);
        }

        return view('livewire.admin.birthday-manager', [
            'birthdays' => $query->paginate(8),
        ]);
    }
}