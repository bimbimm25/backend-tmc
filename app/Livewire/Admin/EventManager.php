<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventManager extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterCategory = '';
    public string $filterLocation = '';

    public bool $isOpen = false;
    public ?int $eventId = null;

    // Field Form Event & Workshop
    public string $category = 'Workshop'; // Pilihan: Workshop, Event, dll.
    public string $title = '';
    public string $location_name = 'Semua Lokasi';
    public string $price = '';
    public ?string $capacity = null;
    public ?string $event_date = null;
    public string $description = '';
    public mixed $image = null;
    public ?string $oldImage = null;
    public bool $is_active = true;

    // Modal Kelola Kategori
    public bool $isCategoryOpen = false;
    public string $categoryName = '';
    public ?int $editingCategoryId = null;

    public string $successMessage = '';

    protected string $paginationTheme = 'tailwind';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterCategory(): void { $this->resetPage(); }
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
        $this->eventId = null;
        $this->category = 'Workshop';
        $this->title = '';
        $this->location_name = 'Semua Lokasi';
        $this->price = '';
        $this->capacity = null;
        $this->event_date = null;
        $this->description = '';
        $this->image = null;
        $this->oldImage = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'location_name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'capacity' => 'nullable|numeric|min:1',
            'event_date' => 'nullable|date',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $imagePath = $this->oldImage;

        if ($this->image) {
            if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                Storage::disk('public')->delete($this->oldImage);
            }
            $imagePath = $this->image->store('events', 'public');
        }

        Event::updateOrCreate(
            ['id' => $this->eventId],
            [
                'type' => Str::slug($this->category), // disesuaikan sesuai kategorinya
                'category' => $this->category,
                'title' => $this->title,
                'location_name' => $this->location_name,
                'price' => $this->price,
                'capacity' => $this->capacity ?: null,
                'event_date' => $this->event_date ?: null,
                'description' => $this->description,
                'image' => $imagePath,
                'is_active' => $this->is_active,
            ]
        );

        $this->successMessage = $this->eventId ? 'Data berhasil diperbarui!' : 'Data baru berhasil ditambahkan!';
        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $event = Event::findOrFail($id);
        $this->eventId = $event->id;
        $this->category = $event->category ?? ($event->type === 'event_workshop' ? 'Workshop' : 'Event');
        $this->title = $event->title;
        $this->location_name = $event->location_name ?? 'Semua Lokasi';
        $this->price = (string) $event->price;
        $this->capacity = $event->capacity ? (string) $event->capacity : null;
        $this->event_date = $event->event_date ? $event->event_date->format('Y-m-d\TH:i') : null;
        $this->description = $event->description ?? '';
        $this->oldImage = $event->image;
        $this->is_active = (bool) $event->is_active;

        $this->isOpen = true;
    }

    public function delete(int $id): void
    {
        $event = Event::findOrFail($id);
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }
        $event->delete();

        $this->successMessage = 'Data berhasil dihapus!';
    }

    // Category Management
    public function openCategoryModal(): void { $this->isCategoryOpen = true; $this->categoryName = ''; $this->editingCategoryId = null; $this->resetValidation(); }
    public function closeCategoryModal(): void { $this->isCategoryOpen = false; $this->categoryName = ''; $this->editingCategoryId = null; $this->resetValidation(); }

    public function saveCategory(): void
    {
        $this->validate(['categoryName' => 'required|string|max:100']);
        if ($this->editingCategoryId) {
            $cat = Category::where('type', 'event')->findOrFail($this->editingCategoryId);
            $oldName = $cat->name;
            $cat->update(['name' => $this->categoryName, 'slug' => Str::slug($this->categoryName)]);
            Event::where('category', $oldName)->update(['category' => $this->categoryName]);
            $this->successMessage = 'Kategori berhasil diperbarui!';
        } else {
            Category::create(['name' => $this->categoryName, 'slug' => Str::slug($this->categoryName), 'type' => 'event']);
            $this->successMessage = 'Kategori baru berhasil ditambahkan!';
        }
        $this->categoryName = '';
        $this->editingCategoryId = null;
    }

    public function editCategory(int $id): void { $cat = Category::where('type', 'event')->findOrFail($id); $this->editingCategoryId = $cat->id; $this->categoryName = $cat->name; }
    public function cancelEditCategory(): void { $this->editingCategoryId = null; $this->categoryName = ''; $this->resetValidation(); }
    public function deleteCategory(int $id): void { Category::where('type', 'event')->findOrFail($id)->delete(); $this->successMessage = 'Kategori berhasil dihapus!'; }

    #[Layout('layouts.admin')]
    public function render()
    {
        // Kecualikan paket birthday
        $query = Event::where('type', '!=', 'birthday_package')->latest();

        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->filterCategory)) {
            $query->where('category', $this->filterCategory);
        }

        if (!empty($this->filterLocation)) {
            $query->where('location_name', $this->filterLocation);
        }

        $categories = Category::where('type', 'event')->orderBy('name', 'asc')->get();

        return view('livewire.admin.event-manager', [
            'events' => $query->paginate(8),
            'categories' => $categories,
        ]);
    }
}