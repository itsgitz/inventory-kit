<?php

namespace App\Livewire\Supplier;

use App\Livewire\Forms\Supplier\CreateForm;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\Attributes\Title;

class Create extends Component
{
    public CreateForm $form;

    public function save()
    {
        $this->validate();

        Supplier::create(
            $this->form->only(['name', 'email', 'phone', 'address'])
        );

        session()->flash('success', 'Supplier created successfully!');

        return $this->redirectRoute('suppliers.manager', navigate: true);
    }

    #[Title('Create Supplier')]
    public function render()
    {
        return view('livewire.supplier.create');
    }
}
