<?php
use App\Models\User;
use Faker\Factory as Faker;

//user create //

test('admin can create appointment', function () {
    $user = User::find(11);
    $form_params = [

    'country_id' => '2',
    'job_id' => '1',
    'consultant_id' => '6',
    'client_id' => '12',
    'time' => '2023-09-13 18.30:00',
    ];

    $response =$this->actingAs($user)->postJson('/api/appointments', $form_params);
    $response->assertStatus(201);
});

//user crrate end //


