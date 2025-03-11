<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $batchSize = 5000; // Insert 5000 records at a time
        $totalRecords = 50000; // Total records to insert

        for ($i = 0; $i < $totalRecords; $i += $batchSize) {
            $students = [];

            for ($j = 0; $j < $batchSize; $j++) {
                $students[] = [
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'age' => rand(18, 30),
                    'created_at' => now()
                ];
            }

            Student::insert($students); // Batch insert
        }
    }
}
