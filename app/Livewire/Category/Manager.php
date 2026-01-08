<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Manager extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public string $sortField = 'name';
    public string $sortDirection = 'asc';
    public ?Category $categoryBeingDeleted = null;
    public string $confirmName = '';
    public bool $showingDeleteModal = false;


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
        return Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->withCount('products')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDeletion($id)
    {
        $this->categoryBeingDeleted = Category::findOrFail($id);
        $this->confirmName = '';
        $this->showingDeleteModal = true;
    }

    public function deleteCategory()
    {
        if (!$this->categoryBeingDeleted) {
            return;
        }

        if ($this->confirmName !== 'delete ' . $this->categoryBeingDeleted->name) {
            $this->addError('confirmName', 'The confirmation text does not match.');
            return;
        }

        $this->categoryBeingDeleted->delete();
        $this->categoryBeingDeleted = null;
        $this->confirmName = '';
        $this->showingDeleteModal = false;

        session()->flash('success', 'Category deleted successfully!');
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedShowingDeleteModal($value)
    {
        if (!$value) {
            $this->categoryBeingDeleted = null;
            $this->confirmName = '';
        }
    }
}
