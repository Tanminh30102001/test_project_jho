<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ManagersTableSeeder::class,
            ContactsTableSeeder::class,
            ListsTableSeeder::class,
            TagsTableSeeder::class,
            OpportunitiesTableSeeder::class,         
            PipelinesTableSeeder::class,
            PipelineColumnsTableSeeder::class,
            OpportunityPipelineColumnsTableSeeder::class,
            OpportunityTagsTableSeeder::class,
            TasksTableSeeder::class,
            ContactListsTableSeeder::class,
            ContactTagsTableSeeder::class,
        ]);
    }
}
