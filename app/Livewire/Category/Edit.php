<?php

namespace App\Livewire\Category;

use App\Livewire\Forms\Category\UpdateForm;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edit extends Component
{
    public UpdateForm $form;

    public function mount(Category $category)
    {
        $this->form->setCategory($category);
    }

    public function save()
    {
        $this->form->update();

        session()->flash('success', 'Category updated successfully!');

        return $this->redirectRoute('categories.manager', navigate: true);
    }

    #[Title('Edit Category')]
    public function render()
    {
        return view('livewire.category.edit');
    }
}
