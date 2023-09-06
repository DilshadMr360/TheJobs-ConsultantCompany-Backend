<?php
use App\Models\User;
use Faker\Factory as Faker;



//login//
test('user can login', function () {
    // $faker = Faker::create();

    $form_params = [
        'email' =>  'admin@example.com',
        'password' => 'password',
    ];

    $response = $this->postJson('/api/login', $form_params);
    $response->assertStatus(200);
});


//login//





