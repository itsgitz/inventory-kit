<?php

namespace App\Livewire\Category;

use Livewire\Attributes\Title;
use Livewire\Component;

class Manager extends Component
{
    public $name = '';

    public $description = '';

    public function save()
    {
    }

    #[Title('Categories')]
    public function render()
    {
        return view('livewire.category.manager');
    }
}
