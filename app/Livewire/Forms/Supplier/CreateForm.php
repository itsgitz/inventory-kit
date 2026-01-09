<?php

namespace App\Livewire\Forms\Supplier;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateForm extends Form
{
    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('required|string|email|max:255')]
    public $email = '';

    #[Validate('required|string|max:20')]
    public $phone = '';

    #[Validate('required|min:5')]
    public $address = '';

    #[Validate('nullable|image|max:2048')]
    public $image = null;
}
