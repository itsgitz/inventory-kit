<?php

namespace App\Livewire\Supplier;

use App\Models\Supplier;
use App\Services\SupplierService;
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
    public ?Supplier $supplierBeingDeleted = null;
    public string $confirmName = '';
    public bool $showingDeleteModal = false;

    protected SupplierService $supplierService;

    public function boot(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    #[Title('Suppliers')]
    public function render()
    {
        $suppliers = $this->getSuppliers();

        return view('livewire.supplier.manager', [
            'suppliers' => $suppliers,
        ]);
    }

    public function getSuppliers()
    {
        return $this->supplierService->getSuppliers(
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
        $this->supplierBeingDeleted = Supplier::findOrFail($id);
        $this->confirmName = '';
        $this->showingDeleteModal = true;
    }

    public function deleteSupplier()
    {
        if (!$this->supplierBeingDeleted) {
            return;
        }

        if ($this->confirmName !== 'delete ' . $this->supplierBeingDeleted->name) {
            $this->addError('confirmName', 'The confirmation text does not match.');
            return;
        }

        $this->supplierService->delete($this->supplierBeingDeleted);
        $this->supplierBeingDeleted = null;
        $this->confirmName = '';
        $this->showingDeleteModal = false;

        session()->flash('success', 'Supplier deleted successfully!');
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
            $this->supplierBeingDeleted = null;
            $this->confirmName = '';
        }
    }
}
