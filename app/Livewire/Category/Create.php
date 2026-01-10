<?php

namespace App\Livewire\Category;

use App\Livewire\Forms\Category\CreateForm;
use App\Services\CategoryService;
use Livewire\Component;
use Livewire\Attributes\Title;

class Create extends Component
{
    public CreateForm $form;

    protected CategoryService $categoryService;

    public function boot(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function save()
    {
        $this->validate();

        $this->categoryService->create(
            $this->form->only(['name', 'description'])
        );

        session()->flash('success', 'Category created successfully!');

        return $this->redirectRoute('categories.manager', navigate: true);
    }

    #[Title('Create Category')]
    public function render()
    {
        return view('livewire.category.create');
    }
}
