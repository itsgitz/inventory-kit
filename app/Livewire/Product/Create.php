<?php

namespace App\Livewire\Product;

use App\Livewire\Forms\Product\CreateForm;
use App\Models\Category;
use App\Models\Supplier;
use App\Services\ProductService;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public CreateForm $form;

    protected ProductService $productService;

    public function boot(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function save()
    {
        $this->validate();

        $data = $this->form->all();
        $image = $this->form->image ?? null;

        $this->productService->create($data, $image);

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
