<?php

namespace App\Livewire\Forms\Supplier;

use App\Models\Supplier;
use App\Services\SupplierService;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateForm extends Form
{
    public ?Supplier $supplier;

    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';

    public function setSupplier(Supplier $supplier)
    {
        $this->supplier = $supplier;
        $this->name = $supplier->name;
        $this->email = $supplier->email;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;
    }

    public function update(SupplierService $supplierService)
    {
        $this->validate([
            'name' => [
                'required',
                'min:3',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
            ],
            'address' => ['required', 'min:5'],
        ]);

        $supplierService->update($this->supplier, [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);
    }
}
