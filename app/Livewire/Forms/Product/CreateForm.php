<?php

namespace App\Livewire\Forms\Product;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateForm extends Form
{
    #[Validate('nullable|exists:categories,id')]
    public $category_id = null;

    #[Validate('nullable|exists:suppliers,id')]
    public $supplier_id = null;

    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('required|unique:products,code')]
    public $code = '';

    #[Validate('required|min:5')]
    public $description = '';

    #[Validate('required|numeric|min:0')]
    public $unit_price = '';

    #[Validate('required|integer|min:0')]
    public $current_stock = 0;
}
