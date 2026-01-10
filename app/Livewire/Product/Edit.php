<?php

namespace App\Livewire\Product;

use App\Livewire\Forms\Product\UpdateForm;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\ProductService;

class Edit extends Component
{
    use WithFileUploads;

    public UpdateForm $form;
    public ProductService $productService;

    public function boot(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function mount(Product $product)
    {
        $this->form->setProduct($product);
    }

    public function save()
    {
        $this->form->update($this->productService);

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
