<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Merchandise;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MerchandiseManager extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterCategory = '';
    public string $filterStatus = '';

    public bool $isOpen = false;
    public ?int $merchandiseId = null;

    // Field Form
    public string $category = '';
    public string $name = '';
    public string $description = '';
    public string $price = '';
    public string $stock_status = 'available';
    public string $purchase_type = 'in_store';
    public mixed $image = null;
    public ?string $oldImage = null;
    public bool $is_featured = false;
    public bool $is_best_seller = false; // <-- Field Best Seller

    // Modal Kategori
    public bool $isCategoryOpen = false;
    public string $categoryName = '';
    public ?int $editingCategoryId = null;

    public string $successMessage = '';

    protected string $paginationTheme = 'tailwind';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingFilterCategory(): void
    {
        $this->resetPage();
    }
    public function updatingFilterStatus(): void
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
        $this->merchandiseId = null;
        $this->category = '';
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->stock_status = 'available';
        $this->purchase_type = 'in_store';
        $this->image = null;
        $this->oldImage = null;
        $this->is_featured = false;
        $this->is_best_seller = false; // <-- Reset status ke false
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'category' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_status' => 'required|string|in:available,out_of_stock,pre_order',
            'purchase_type' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured' => 'boolean',
            'is_best_seller' => 'boolean', // <-- Validasi boolean
        ]);

        $imagePath = $this->oldImage;

        if ($this->image) {
            if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                Storage::disk('public')->delete($this->oldImage);
            }
            $imagePath = $this->image->store('merchandise', 'public');
        }

        Merchandise::updateOrCreate(
            ['id' => $this->merchandiseId],
            [
                'category' => $this->category,
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
                'price' => $this->price,
                'stock_status' => $this->stock_status,
                'purchase_type' => $this->purchase_type,
                'image' => $imagePath,
                'is_featured' => $this->is_featured,
                'is_best_seller' => $this->is_best_seller, // <-- Simpan ke database
            ]
        );

        $this->successMessage = $this->merchandiseId ? 'Merchandise berhasil diperbarui!' : 'Merchandise baru berhasil ditambahkan!';
        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $item = Merchandise::findOrFail($id);
        $this->merchandiseId = $item->id;
        $this->category = $item->category;
        $this->name = $item->name;
        $this->description = $item->description ?? '';
        $this->price = (string) $item->price;
        $this->stock_status = $item->stock_status ?? 'available';

        // Konversi value lama ke value constraint
        $pType = $item->purchase_type ?? 'in_store';
        if ($pType === 'In Store')
            $pType = 'in_store';
        if ($pType === 'WhatsApp Order')
            $pType = 'whatsapp';
        if ($pType === 'Online / Delivery')
            $pType = 'online';
        $this->purchase_type = $pType;

        $this->oldImage = $item->image;
        $this->is_featured = (bool) $item->is_featured;
        $this->is_best_seller = (bool) $item->is_best_seller; // <-- Load nilai dari model

        $this->isOpen = true;
    }

    public function delete(int $id): void
    {
        $item = Merchandise::findOrFail($id);
        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();

        $this->successMessage = 'Merchandise berhasil dihapus!';
    }

    // Category Management Methods
    public function openCategoryModal(): void
    {
        $this->isCategoryOpen = true;
        $this->categoryName = '';
        $this->editingCategoryId = null;
        $this->resetValidation();
    }

    public function closeCategoryModal(): void
    {
        $this->isCategoryOpen = false;
        $this->categoryName = '';
        $this->editingCategoryId = null;
        $this->resetValidation();
    }

    public function saveCategory(): void
    {
        $this->validate(['categoryName' => 'required|string|max:100']);
        if ($this->editingCategoryId) {
            $cat = Category::where('type', 'merchandise')->findOrFail($this->editingCategoryId);
            $oldName = $cat->name;
            $cat->update(['name' => $this->categoryName, 'slug' => Str::slug($this->categoryName)]);
            Merchandise::where('category', $oldName)->update(['category' => $this->categoryName]);
            $this->successMessage = 'Kategori berhasil diperbarui!';
        } else {
            Category::create(['name' => $this->categoryName, 'slug' => Str::slug($this->categoryName), 'type' => 'merchandise']);
            $this->successMessage = 'Kategori baru berhasil ditambahkan!';
        }
        $this->categoryName = '';
        $this->editingCategoryId = null;
    }

    public function editCategory(int $id): void
    {
        $cat = Category::where('type', 'merchandise')->findOrFail($id);
        $this->editingCategoryId = $cat->id;
        $this->categoryName = $cat->name;
    }

    public function cancelEditCategory(): void
    {
        $this->editingCategoryId = null;
        $this->categoryName = '';
        $this->resetValidation();
    }

    public function deleteCategory(int $id): void
    {
        Category::where('type', 'merchandise')->findOrFail($id)->delete();
        $this->successMessage = 'Kategori berhasil dihapus!';
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $query = Merchandise::latest();

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->filterCategory)) {
            $query->where('category', $this->filterCategory);
        }

        if (!empty($this->filterStatus)) {
            $query->where('stock_status', $this->filterStatus);
        }

        $categoryList = Category::where('type', 'merchandise')->orderBy('name', 'asc')->get();

        return view('livewire.admin.merchandise-manager', [
            'merchandises' => $query->paginate(8),
            'categories' => $categoryList,
        ]);
    }
}