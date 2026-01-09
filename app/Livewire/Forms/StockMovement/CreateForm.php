<?php

namespace App\Livewire\Forms\StockMovement;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateForm extends Form
{
    #[Validate('required|exists:products,id')]
    public $product_id = null;

    #[Validate('required|in:IN,OUT')]
    public $type = 'IN';

    #[Validate('required|integer|min:1')]
    public $quantity = 1;

    #[Validate('required|min:5|max:255')]
    public $reason = '';

    #[Validate('nullable|max:500')]
    public $notes = '';
}
