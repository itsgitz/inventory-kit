<?php

use App\Models\Category;
use App\Models\Product;

test('category has fillable attributes', function () {
    $category = new Category();
    
    expect($category->getFillable())->toBe(['name', 'description']);
});

test('category uses ulid as primary key', function () {
    $category = Category::factory()->create();
    
    expect($category->getKeyType())->toBe('string');
    expect($category->getIncrementing())->toBeFalse();
    expect($category->id)->toBeString();
    expect(strlen($category->id))->toBe(26); // ULID length
});

test('category has products relationship', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);
    
    expect($category->products)->toHaveCount(1);
    expect($category->products->first()->id)->toBe($product->id);
});

test('category can have multiple products', function () {
    $category = Category::factory()->create();
    Product::factory()->count(3)->create(['category_id' => $category->id]);
    
    expect($category->products)->toHaveCount(3);
});

test('category uses soft deletes', function () {
    $category = Category::factory()->create();
    $categoryId = $category->id;
    
    $category->delete();
    
    expect(Category::find($categoryId))->toBeNull();
    expect(Category::withTrashed()->find($categoryId))->not->toBeNull();
    expect(Category::withTrashed()->find($categoryId)->trashed())->toBeTrue();
});

test('category can be restored after soft delete', function () {
    $category = Category::factory()->create();
    $categoryId = $category->id;
    
    $category->delete();
    expect(Category::find($categoryId))->toBeNull();
    
    Category::withTrashed()->find($categoryId)->restore();
    expect(Category::find($categoryId))->not->toBeNull();
});

test('category can count products', function () {
    $category = Category::factory()->create();
    Product::factory()->count(5)->create(['category_id' => $category->id]);
    
    $categoryWithCount = Category::withCount('products')->find($category->id);
    
    expect($categoryWithCount->products_count)->toBe(5);
});
