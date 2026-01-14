<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('admin can see users list', function () {
    $admin = User::factory()->create(['access_type' => 1]); // SUPERADMIN

    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    $response->assertStatus(200);
});

test('admin can create user', function () {
    $admin = User::factory()->create(['access_type' => 1]);

    $response = $this->actingAs($admin)->post(route('admin.users.doCreate'), [
        'username' => 'newuser',
        'access_type' => 3, // USER
        'class' => 'XI-RPL',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', ['username' => 'newuser']);
});
