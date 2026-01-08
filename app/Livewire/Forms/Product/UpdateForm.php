<?php

namespace App\Livewire\Forms\Product;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateForm extends Form
{
    public ?Product $product;

    public $category_id = null;
    public $supplier_id = null;
    public $name = '';
    public $code = '';
    public $description = '';
    public $unit_price = '';
    public $current_stock = 0;

    public function setProduct(Product $product)
    {
        $this->product = $product;
        $this->category_id = $product->category_id;
        $this->supplier_id = $product->supplier_id;
        $this->name = $product->name;
        $this->code = $product->code;
        $this->description = $product->description;
        $this->unit_price = $product->unit_price;
        $this->current_stock = $product->current_stock;
    }

    public function update()
    {
        $this->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'name' => ['required', 'min:3'],
            'code' => [
                'required',
                Rule::unique('products', 'code')->ignore($this->product->id),
            ],
            'description' => ['required', 'min:5'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'current_stock' => ['required', 'integer', 'min:0'],
        ]);

        $this->product->update([
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'unit_price' => $this->unit_price,
            'current_stock' => $this->current_stock,
        ]);
    }
}
