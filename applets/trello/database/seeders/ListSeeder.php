<?php

namespace Trello\Database\Seeders;

use Illuminate\Database\Seeder;
use Trello\Models\Board;
use Trello\Models\TrelloList;

class ListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boards = Board::all();

        if ($boards->isEmpty()) {
            $this->command->warn('No boards found. Please run BoardSeeder first.');
            return;
        }

        $listTitles = ['To Do', 'In Progress', 'Done'];

        foreach ($boards as $board) {
            foreach ($listTitles as $index => $title) {
                TrelloList::create([
                    'board_id' => $board->id,
                    'title' => $title,
                    'position' => ($index + 1) * 10,
                ]);
            }
        }

        $this->command->info('Created 3 lists for each board');
    }
}
