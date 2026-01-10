<?php

namespace App\Livewire\Category;

use App\Livewire\Forms\Category\UpdateForm;
use App\Models\Category;
use App\Services\CategoryService;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edit extends Component
{
    public UpdateForm $form;
    public CategoryService $categoryService; 

    public function boot(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function save()
    {
        $this->form->update($this->categoryService);

        session()->flash('success', 'Category updated successfully!');

        return $this->redirectRoute('categories.manager', navigate: true);
    }

    #[Title('Edit Category')]
    public function render()
    {
        return view('livewire.category.edit');
    }
}
