<?php

use App\Models\Category;
use App\Models\User;

test('unauthenticated user cannot access category manager', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->get(route('categories.manager'));

    $response->assertRedirect(route('login'));
});

test('unauthenticated user cannot access category create page', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->get(route('categories.create'));

    $response->assertRedirect(route('login'));
});

test('unauthenticated user cannot access category edit page', function () {
    /** @var \Tests\TestCase $this */
    $category = Category::factory()->create();

    $response = $this->get(route('categories.edit', $category));

    $response->assertRedirect(route('login'));
});

test('authenticated user can access category manager', function () {
    /** @var \Tests\TestCase $this */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('categories.manager'));

    $response->assertStatus(200);
});

test('authenticated user can access category create page', function () {
    /** @var \Tests\TestCase $this */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('categories.create'));

    $response->assertStatus(200);
});

test('authenticated user can access category edit page', function () {
    /** @var \Tests\TestCase $this */
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $response = $this->actingAs($user)->get(route('categories.edit', $category));

    $response->assertStatus(200);
});
