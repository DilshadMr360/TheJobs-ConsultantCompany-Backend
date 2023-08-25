<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $countries = ['USA', 'Australia', 'Canada', 'United Kingdom', 'India', 'Germany'];

        $countriesData = [];
        foreach ($countries as $country) {
            $countriesData[] = ['name' => $country];
        }

        DB::table('countries')->insert($countriesData);


        $jobs = ['Software Engineer', 'Accountant', 'Graphic Designer', 'Quality Assurance', 'Marketing Coordinator', 'Driver', ];

        $jobsData = [];
        foreach ($jobs as $job) {
            $JobsData[] = ['name' => $job];
        }

        DB::table('jobs')->insert($JobsData);
    }
}