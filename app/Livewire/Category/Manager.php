<?php

namespace App\Livewire\Category;

use Livewire\Attributes\Title;
use Livewire\Component;

class Manager extends Component
{
    #[Title('Categories')]
    public function render()
    {
        return view('livewire.category.manager');
    }
}
