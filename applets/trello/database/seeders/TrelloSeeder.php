<?php

namespace Trello\Database\Seeders;

use Illuminate\Database\Seeder;

class TrelloSeeder extends Seeder
{
    /**
     * Run the Trello package database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Trello package data...');

        $this->call([
            BoardSeeder::class,
            ListSeeder::class,
            CardSeeder::class,
        ]);

        $this->command->info('Trello package seeding completed!');
    }
}
