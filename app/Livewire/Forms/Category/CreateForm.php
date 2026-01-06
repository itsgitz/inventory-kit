<?php

namespace App\Livewire\Forms\Category;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateForm extends Form
{
    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('required|min:5')]
    public $description = '';
}
