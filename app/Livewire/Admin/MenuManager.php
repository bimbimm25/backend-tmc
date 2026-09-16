<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuManager extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterCategory = '';
    public string $filterLocation = '';

    public bool $isOpen = false;
    public ?int $menuId = null;

    // Field Form Menu
    public string $category = '';
    public string $name = '';
    public string $description = '';
    public string $price = '';
    public string $purchase_option = 'Dine In Only'; // <-- Default disesuaikan
    public string $location = 'all';
    public mixed $image = null;
    public ?string $oldImage = null;
    public bool $is_featured = false;
    public bool $is_bestseller = false;
    public bool $is_recommended = false;

    // Modal Kategori
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
        $this->menuId = null;
        $this->category = '';
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->purchase_option = 'Dine In Only';
        $this->location = 'all';
        $this->image = null;
        $this->oldImage = null;
        $this->is_featured = false;
        $this->is_bestseller = false;
        $this->is_recommended = false;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'category' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'purchase_option' => 'required|string',
            'location' => 'required|string|in:all,heavenland,pondok_mutiara',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured' => 'boolean',
            'is_bestseller' => 'boolean',
            'is_recommended' => 'boolean',
        ]);

        $imagePath = $this->oldImage;

        if ($this->image) {
            if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                Storage::disk('public')->delete($this->oldImage);
            }
            $imagePath = $this->image->store('menus', 'public');
        }

        Menu::updateOrCreate(
            ['id' => $this->menuId],
            [
                'category' => $this->category,
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'purchase_option' => $this->purchase_option,
                'location' => $this->location,
                'image' => $imagePath,
                'is_featured' => $this->is_featured,
                'is_bestseller' => $this->is_bestseller,
                'is_recommended' => $this->is_recommended,
            ]
        );

        $this->successMessage = $this->menuId ? 'Menu berhasil diperbarui!' : 'Menu baru berhasil ditambahkan!';
        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $menu = Menu::findOrFail($id);
        $this->menuId = $menu->id;
        $this->category = $menu->category;
        $this->name = $menu->name;
        $this->description = $menu->description ?? '';
        $this->price = (string) $menu->price;
        
        // Pemetaan value lama jika ada
        $pOption = $menu->purchase_option ?? 'Dine In Only';
        if ($pOption === 'In Store' || $pOption === 'in_store') $pOption = 'Dine In Only';
        if ($pOption === 'WhatsApp Order' || $pOption === 'Online / Delivery') $pOption = 'Take Away';
        $this->purchase_option = $pOption;

        $this->location = $menu->location ?? 'all';
        $this->oldImage = $menu->image;
        $this->is_featured = (bool) $menu->is_featured;
        $this->is_bestseller = (bool) $menu->is_bestseller;
        $this->is_recommended = (bool) $menu->is_recommended;

        $this->isOpen = true;
    }

    public function delete(int $id): void
    {
        $menu = Menu::findOrFail($id);
        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);
        }
        $menu->delete();

        $this->successMessage = 'Menu berhasil dihapus!';
    }

    // Modal Kategori Methods
    public function openCategoryModal(): void { $this->isCategoryOpen = true; $this->categoryName = ''; $this->editingCategoryId = null; $this->resetValidation(); }
    public function closeCategoryModal(): void { $this->isCategoryOpen = false; $this->categoryName = ''; $this->editingCategoryId = null; $this->resetValidation(); }

    public function saveCategory(): void
    {
        $this->validate(['categoryName' => 'required|string|max:100']);
        if ($this->editingCategoryId) {
            $cat = Category::where('type', 'menu')->findOrFail($this->editingCategoryId);
            $oldName = $cat->name;
            $cat->update(['name' => $this->categoryName, 'slug' => Str::slug($this->categoryName)]);
            Menu::where('category', $oldName)->update(['category' => $this->categoryName]);
            $this->successMessage = 'Kategori menu berhasil diperbarui!';
        } else {
            Category::create(['name' => $this->categoryName, 'slug' => Str::slug($this->categoryName), 'type' => 'menu']);
            $this->successMessage = 'Kategori menu baru berhasil ditambahkan!';
        }
        $this->categoryName = '';
        $this->editingCategoryId = null;
    }

    public function editCategory(int $id): void { $cat = Category::where('type', 'menu')->findOrFail($id); $this->editingCategoryId = $cat->id; $this->categoryName = $cat->name; }
    public function cancelEditCategory(): void { $this->editingCategoryId = null; $this->categoryName = ''; $this->resetValidation(); }
    public function deleteCategory(int $id): void { Category::where('type', 'menu')->findOrFail($id)->delete(); $this->successMessage = 'Kategori berhasil dihapus!'; }

    #[Layout('layouts.admin')]
    public function render()
    {
        $query = Menu::latest();

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->filterCategory)) {
            $query->where('category', $this->filterCategory);
        }

        if (!empty($this->filterLocation)) {
            $query->where('location', $this->filterLocation);
        }

        $categoryList = Category::where('type', 'menu')->orderBy('name', 'asc')->get();

        return view('livewire.admin.menu-manager', [
            'menus' => $query->paginate(8),
            'categories' => $categoryList,
        ]);
    }
}