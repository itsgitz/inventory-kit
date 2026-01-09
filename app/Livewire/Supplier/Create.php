<?php

namespace App\Livewire\Supplier;

use App\Livewire\Forms\Supplier\CreateForm;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public CreateForm $form;

    public function save()
    {
        $this->validate();

        $data = $this->form->only(['name', 'email', 'phone', 'address']);

        if ($this->form->image) {
            $data['image'] = $this->form->image->store('suppliers', 'public');
        }

        Supplier::create($data);

        session()->flash('success', 'Supplier created successfully!');

        return $this->redirectRoute('suppliers.manager', navigate: true);
    }

    #[Title('Create Supplier')]
    public function render()
    {
        return view('livewire.supplier.create');
    }
}
