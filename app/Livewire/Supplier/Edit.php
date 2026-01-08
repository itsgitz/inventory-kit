<?php

namespace App\Livewire\Supplier;

use App\Livewire\Forms\Supplier\UpdateForm;
use App\Models\Supplier;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edit extends Component
{
    public UpdateForm $form;

    public function mount(Supplier $supplier)
    {
        $this->form->setSupplier($supplier);
    }

    public function save()
    {
        $this->form->update();

        session()->flash('success', 'Supplier updated successfully!');

        return $this->redirectRoute('suppliers.manager', navigate: true);
    }

    #[Title('Edit Supplier')]
    public function render()
    {
        return view('livewire.supplier.edit');
    }
}
