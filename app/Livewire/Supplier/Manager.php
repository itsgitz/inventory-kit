<?php

namespace App\Livewire\Supplier;

use App\Models\Supplier;
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
        return Supplier::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
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

        $this->supplierBeingDeleted->delete();
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
