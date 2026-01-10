<?php

use App\Livewire\Category\Edit;
use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('category edit page can be rendered', function () {
    $category = Category::factory()->create();

    $response = $this->get(route('categories.edit', $category));

    $response->assertStatus(200);
    $response->assertSeeLivewire(Edit::class);
});

test('category edit page loads category data', function () {
    $category = Category::factory()->create([
        'name' => 'Original Name',
        'description' => 'Original Description',
    ]);

    Livewire::test(Edit::class, ['category' => $category])
        ->assertSet('form.name', 'Original Name')
        ->assertSet('form.description', 'Original Description');
});

test('category can be updated with valid data', function () {
    $category = Category::factory()->create([
        'name' => 'Original Name',
        'description' => 'Original Description',
    ]);

    Livewire::test(Edit::class, ['category' => $category])
        ->set('form.name', 'Updated Name')
        ->set('form.description', 'Updated Description')
        ->call('save')
        ->assertSessionHas('success', 'Category updated successfully!')
        ->assertRedirect(route('categories.manager'));

    $category->refresh();
    expect($category->name)->toBe('Updated Name')
        ->and($category->description)->toBe('Updated Description');
});

test('category update requires name', function () {
    $category = Category::factory()->create();

    Livewire::test(Edit::class, ['category' => $category])
        ->set('form.name', '')
        ->set('form.description', 'Valid description')
        ->call('save')
        ->assertHasErrors(['form.name']);
});

test('category update requires name with minimum 3 characters', function () {
    $category = Category::factory()->create();

    Livewire::test(Edit::class, ['category' => $category])
        ->set('form.name', 'Ab')
        ->set('form.description', 'Valid description')
        ->call('save')
        ->assertHasErrors(['form.name']);
});

test('category update requires unique name excluding current category', function () {
    $category1 = Category::factory()->create(['name' => 'Electronics']);
    $category2 = Category::factory()->create(['name' => 'Clothing']);

    // Should be able to update category2 with its own name
    Livewire::test(Edit::class, ['category' => $category2])
        ->set('form.name', 'Clothing')
        ->set('form.description', 'Valid description')
        ->call('save')
        ->assertHasNoErrors();

    // Should not be able to update category2 with category1's name
    Livewire::test(Edit::class, ['category' => $category2])
        ->set('form.name', 'Electronics')
        ->set('form.description', 'Valid description')
        ->call('save')
        ->assertHasErrors(['form.name']);
});

test('category update requires description', function () {
    $category = Category::factory()->create();

    Livewire::test(Edit::class, ['category' => $category])
        ->set('form.name', 'Valid Name')
        ->set('form.description', '')
        ->call('save')
        ->assertHasErrors(['form.description']);
});

test('category update requires description with minimum 5 characters', function () {
    $category = Category::factory()->create();

    Livewire::test(Edit::class, ['category' => $category])
        ->set('form.name', 'Valid Name')
        ->set('form.description', 'Test')
        ->call('save')
        ->assertHasErrors(['form.description']);
});

test('category can be updated without changing name', function () {
    $category = Category::factory()->create([
        'name' => 'Electronics',
        'description' => 'Original Description',
    ]);

    Livewire::test(Edit::class, ['category' => $category])
        ->set('form.name', 'Electronics')
        ->set('form.description', 'Updated Description')
        ->call('save')
        ->assertHasNoErrors();

    $category->refresh();
    expect($category->name)->toBe('Electronics')
        ->and($category->description)->toBe('Updated Description');
});
