<?php
use App\Models\User;
use Faker\Factory as Faker;



//login//


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



// register

// test('user can register', function () {
//     // $faker = Faker::create();

//     $form_params = [
//         'name' => 'hello',
//         'email' =>  'hello@gmail.com',
//         'phone' =>  '07161781925',
//         'password' => 'hello1234',
//         'password_confirmation' => 'hello1234',
//     ];

//     $response = $this->postJson('/api/register', $form_params);
//     $response->assertStatus(200);
// });


//regiter end//

test('admin can create user', function () {
    $user = User::find(1);
    $faker = Faker::create();
    $form_params = [
        'name' => $faker->name(),
        'email' =>  $faker->unique()->safeEmail(),
        'phone' => '0716663262',
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
