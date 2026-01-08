<?php

namespace App\Livewire\Category;

use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Manager extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    #[Title('Categories')]
    public function render()
    {
        $categories = $this->getCategories();

        return view('livewire.category.manager', [
            'categories' => $categories,
        ]);
    }

    public function getCategories()
    {
        return \App\Models\Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->withCount('products')
            ->latest()
            ->paginate($this->perPage);
    }

    public function delete($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        $category->delete();

        session()->flash('success', 'Category deleted successfully!');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
