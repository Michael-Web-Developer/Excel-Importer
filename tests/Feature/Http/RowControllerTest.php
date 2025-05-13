<?php

use App\Models\Row;
use App\Models\User;

test('returns grouped rows by date', function () {
    $user = User::factory()->create();

    Row::factory()
        ->count(3)
        ->createMany([
            [
                'id' => 1,
                'name' => 'John Doe',
                'date' => '2023-01-01',
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'date' => '2023-01-01',
            ],
            [
                'id' => 3,
                'name' => 'Alice Johnson',
                'date' => '2023-01-02',
            ],
        ]);

    $response = $this
        ->actingAs($user)
        ->getJson('/api/rows');


    $response->assertStatus(200);
    $response->assertJson([
        '01.01.2023' => [
            [
                'id' => 1,
                'name' => 'John Doe',
                'date' => '01.01.2023',
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'date' => '01.01.2023',
            ],
        ],
        '02.01.2023' => [
            [
                'id' => 3,
                'name' => 'Alice Johnson',
                'date' => '02.01.2023',
            ],
        ],
    ]);
});

test('rejects for guest user', function () {
    $response = $this->get('/api/rows');

    $response->assertStatus(401);
    $response->assertJson([
        'message' => 'Unauthorized',
    ]);
});
