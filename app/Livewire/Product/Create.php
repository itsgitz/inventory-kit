<?php

namespace App\Livewire\Product;

use App\Livewire\Forms\Product\CreateForm;
use App\Models\Category;
use App\Models\Product;
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

        $data = $this->form->all();

        if ($this->form->image) {
            $data['image'] = $this->form->image->store('products', 'public');
        }

        Product::create($data);

        session()->flash('success', 'Product created successfully!');

        return $this->redirectRoute('products.manager', navigate: true);
    }

    #[Title('Create Product')]
    public function render()
    {
        return view('livewire.product.create', [
            'categories' => Category::all(),
            'suppliers' => Supplier::all(),
        ]);
    }
}
