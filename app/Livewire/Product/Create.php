<?php

namespace App\Livewire\Product;

use App\Livewire\Forms\Product\CreateForm;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\Attributes\Title;

class Create extends Component
{
    public CreateForm $form;

    public function save()
    {
        $this->validate();

        Product::create(
            $this->form->all()
        );

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
