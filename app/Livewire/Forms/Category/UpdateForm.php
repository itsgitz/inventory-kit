<?php

namespace App\Livewire\Forms\Category;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateForm extends Form
{
    public ?Category $category;

    public $name = '';
    public $description = '';

    public function setCategory(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function update(CategoryService $categoryService)
    {
        $this->validate([
            'name' => [
                'required',
                'min:3',
                Rule::unique('categories', 'name')->ignore($this->category->id),
            ],
            'description' => ['required', 'min:5'],
        ]);

        $categoryService->update($this->category, [
            'name' => $this->name,
            'description' => $this->description,
        ]);
    }
}
