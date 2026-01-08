<?php

namespace App\Livewire\Product;

use App\Livewire\Forms\Product\UpdateForm;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edit extends Component
{
    public UpdateForm $form;

    public function mount(Product $product)
    {
        $this->form->setProduct($product);
    }

    public function save()
    {
        $this->form->update();

        session()->flash('success', 'Product updated successfully!');

        return $this->redirectRoute('products.manager', navigate: true);
    }

    #[Title('Edit Product')]
    public function render()
    {
        return view('livewire.product.edit', [
            'categories' => Category::all(),
            'suppliers' => Supplier::all(),
        ]);
    }
}
