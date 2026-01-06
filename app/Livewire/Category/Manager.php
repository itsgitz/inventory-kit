<?php

namespace App\Livewire\Category;

use App\Livewire\Forms\Category\CreateForm;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

class Manager extends Component
{
    public CreateForm $form;

    public function save()
    {
        $this->validate();

        Category::create(
            $this->form->only(['name', 'description'])
        );

        session()->flash('success', 'Category created successfully!');

        return $this->redirectRoute('categories.manager');
    }

    #[Title('Categories')]
    public function render()
    {
        return view('livewire.category.manager');
    }
}
