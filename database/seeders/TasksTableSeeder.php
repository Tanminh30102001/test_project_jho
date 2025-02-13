<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $manager_id = DB::table('managers')->inRandomOrder()->first()->id;
        $contact=DB::table('contacts')->inRandomOrder()->first()->id;
        $opportunities=DB::table('opportunities')->inRandomOrder()->first()->id;

        foreach (range(1, 20) as $index) {
            DB::table('tasks')->insert([
                'name' => $faker->sentence(3),
                'description'=>$faker->sentence(3),
                'contact_id' => $contact,
                'manager_id' =>  $manager_id,
                'opportunity_id'=>$opportunities,
                'status'=>$faker->randomElement(['pending', 'completed', 'in_progress']),
                'due_date'=>$faker->dateTimeBetween('now', '+1 month'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
