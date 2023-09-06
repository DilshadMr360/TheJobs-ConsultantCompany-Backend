<?php
use App\Models\User;
use Faker\Factory as Faker;


//user create //

test('admin can create user', function () {
    $user = User::find(11);
    $faker = Faker::create();
    $form_params = [
        'name' => $faker->name(),
        'email' =>  $faker->unique()->safeEmail(),
        'phone' => '0716663267',
            'role' => 'consultant',
            'password' => 'hello124',
            'password_confirmation' => 'hello124',
            'job_id' => ['1'],
            'country_id' => ['1'],
    ];

    $response = $this->actingAs($user)->postJson('/api/users', $form_params);
///   dd($response->content());
    $response->assertStatus(201);
});

//user crrate end //




