<?php

namespace App\Livewire\Supplier;

use App\Livewire\Forms\Supplier\CreateForm;
use App\Services\SupplierService;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public CreateForm $form;

    protected SupplierService $supplierService;

    public function boot(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function save()
    {
        $this->validate();

        $data = $this->form->only(['name', 'email', 'phone', 'address']);
        $image = $this->form->image ?? null;

        $this->supplierService->create($data, $image);

        session()->flash('success', 'Supplier created successfully!');

        return $this->redirectRoute('suppliers.manager', navigate: true);
    }

    #[Title('Create Supplier')]
    public function render()
    {
        return view('livewire.supplier.create');
    }
}
