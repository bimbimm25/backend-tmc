<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Post;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostManager extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterCategory = '';

    // Modal Post / Artikel
    public bool $isOpen = false;
    public ?int $postId = null;
    public string $title = '';
    public string $slug = '';
    public ?int $category_id = null;
    public string $content = '';
    
    // SEO Properties
    public string $meta_title = '';
    public string $focus_keyword = '';
    public string $meta_description = '';
    public string $meta_keywords = '';

    public mixed $image = null;
    public ?string $oldImage = null;
    public bool $is_published = true;

    // Modal Kelola Kategori (CRUD)
    public bool $isCategoryModalOpen = false;
    public ?int $categoryId = null;
    public string $categoryName = '';

    public string $successMessage = '';

    protected string $paginationTheme = 'tailwind';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterCategory(): void { $this->resetPage(); }

    // Otomatis update slug saat judul diketik jika belum ada slug kustom
    public function updatedTitle($value): void
    {
        if (!$this->postId) {
            $this->slug = Str::slug($value);
            if (empty($this->meta_title)) {
                $this->meta_title = $value;
            }
        }
    }

    public function openModal(): void
    {
        $this->resetArticleForm();
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->resetArticleForm();
    }

    private function resetArticleForm(): void
    {
        $this->postId = null;
        $this->title = '';
        $this->slug = '';
        $this->category_id = null;
        $this->content = '';
        $this->meta_title = '';
        $this->focus_keyword = '';
        $this->meta_description = '';
        $this->meta_keywords = '';
        $this->image = null;
        $this->oldImage = null;
        $this->is_published = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $this->postId,
            'category_id' => 'nullable|exists:blog_categories,id',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'focus_keyword' => 'nullable|string|max:100',
            'meta_keywords' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ], [
            'title.required' => 'Judul artikel wajib diisi.',
            'slug.required' => 'Slug URL wajib diisi.',
            'slug.unique' => 'Slug URL ini sudah digunakan oleh artikel lain.',
            'content.required' => 'Isi artikel wajib diisi.',
            'meta_description.max' => 'Meta description disarankan maksimal 160 karakter.',
        ]);

        $imagePath = $this->oldImage;
        if ($this->image) {
            if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                Storage::disk('public')->delete($this->oldImage);
            }
            $imagePath = $this->image->store('blog-posts', 'public');
        }

        Post::updateOrCreate(
            ['id' => $this->postId],
            [
                'title' => $this->title,
                'slug' => Str::slug($this->slug ?: $this->title),
                'category_id' => $this->category_id,
                'content' => $this->content,
                'meta_title' => $this->meta_title ?: $this->title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords ?: $this->focus_keyword,
                'image' => $imagePath,
                'is_published' => $this->is_published,
            ]
        );

        $this->successMessage = $this->postId ? 'Artikel SEO berhasil diperbarui!' : 'Artikel SEO baru berhasil diterbitkan!';
        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $item = Post::findOrFail($id);
        $this->postId = $item->id;
        $this->title = $item->title;
        $this->slug = $item->slug;
        $this->category_id = $item->category_id;
        $this->content = $item->content;
        $this->meta_title = $item->meta_title ?? $item->title;
        $this->meta_description = $item->meta_description ?? '';
        $this->meta_keywords = $item->meta_keywords ?? '';
        $this->focus_keyword = $item->meta_keywords ?? '';
        $this->oldImage = $item->image;
        $this->is_published = (bool) $item->is_published;

        $this->isOpen = true;
    }

    public function delete(int $id): void
    {
        $item = Post::findOrFail($id);
        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        $this->successMessage = 'Artikel berhasil dihapus!';
    }

    // CRUD Kategori Blog
    public function openCategoryModal(): void
    {
        $this->resetCategoryForm();
        $this->isCategoryModalOpen = true;
    }

    public function closeCategoryModal(): void
    {
        $this->isCategoryModalOpen = false;
        $this->resetCategoryForm();
    }

    public function resetCategoryForm(): void
    {
        $this->categoryId = null;
        $this->categoryName = '';
        $this->resetValidation();
    }

    public function saveCategory(): void
    {
        $this->validate([
            'categoryName' => 'required|string|max:100|unique:blog_categories,name,' . $this->categoryId,
        ], [
            'categoryName.required' => 'Nama kategori wajib diisi.',
            'categoryName.unique' => 'Nama kategori ini sudah terdaftar.',
        ]);

        BlogCategory::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'name' => $this->categoryName,
                'slug' => Str::slug($this->categoryName),
            ]
        );

        $this->successMessage = $this->categoryId ? 'Kategori berhasil diubah!' : 'Kategori baru berhasil ditambahkan!';
        $this->resetCategoryForm();
    }

    public function editCategory(int $id): void
    {
        $cat = BlogCategory::findOrFail($id);
        $this->categoryId = $cat->id;
        $this->categoryName = $cat->name;
    }

    public function deleteCategory(int $id): void
    {
        $cat = BlogCategory::findOrFail($id);
        $cat->delete();
        $this->successMessage = 'Kategori berhasil dihapus!';
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $query = Post::with('category')->latest();

        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->filterCategory)) {
            $query->where('category_id', $this->filterCategory);
        }

        return view('livewire.admin.post-manager', [
            'posts' => $query->paginate(10),
            'categories' => BlogCategory::withCount('posts')->get(),
        ]);
    }
}