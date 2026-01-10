<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Services\ProductService;
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
    public ?Product $productBeingDeleted = null;
    public string $confirmName = '';
    public bool $showingDeleteModal = false;

    protected ProductService $productService;

    public function boot(ProductService $productService)
    {
        $this->productService = $productService;
    }

    #[Title('Products')]
    public function render()
    {
        $products = $this->getProducts();

        return view('livewire.product.manager', [
            'products' => $products,
        ]);
    }

    public function getProducts()
    {
        return $this->productService->getProducts(
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
        $this->productBeingDeleted = Product::findOrFail($id);
        $this->confirmName = '';
        $this->showingDeleteModal = true;
    }

    public function deleteProduct()
    {
        if (!$this->productBeingDeleted) {
            return;
        }

        if ($this->confirmName !== 'delete ' . $this->productBeingDeleted->name) {
            $this->addError('confirmName', 'The confirmation text does not match.');
            return;
        }

        $this->productService->delete($this->productBeingDeleted);
        $this->productBeingDeleted = null;
        $this->confirmName = '';
        $this->showingDeleteModal = false;

        session()->flash('success', 'Product deleted successfully!');
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
            $this->productBeingDeleted = null;
            $this->confirmName = '';
        }
    }
}
