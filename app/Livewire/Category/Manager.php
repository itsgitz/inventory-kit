<?php

namespace App\Livewire\Category;

use App\Models\Category;
use App\Services\CategoryService;
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

    protected CategoryService $categoryService;

    public function boot(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }


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
        return $this->categoryService->getCategories(
            $this->search,
            $this->sortField,
            $this->sortDirection,
            $this->perPage
        );
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

        $this->categoryService->delete($this->categoryBeingDeleted);
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
