<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $manager_id = DB::table('managers')->inRandomOrder()->first()->id;
        foreach (range(1, 20) as $index) {
            DB::table('contacts')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'manager_id'=>$manager_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
