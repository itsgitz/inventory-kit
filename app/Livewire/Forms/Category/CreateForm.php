<?php

namespace App\Livewire\Forms\Category;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateForm extends Form
{
    #[Validate('required|min:3|unique:categories,name')]
    public $name = '';

    #[Validate('required|min:5')]
    public $description = '';
}
