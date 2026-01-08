<?php

namespace App\Livewire\Category;

use App\Livewire\Forms\Category\CreateForm;
use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Title;

class Create extends Component
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

    #[Title('Create Category')]
    public function render()
    {
        return view('livewire.category.create');
    }
}
