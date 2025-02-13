<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class OpportunitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $manager_id = DB::table('managers')->inRandomOrder()->first()->id;
        foreach (range(1, 20) as $index) {
            DB::table('opportunities')->insert([
                'name' => $faker->sentence(3),
                'contact_id' => rand(1, 20),
                'manager_id' =>  $manager_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
