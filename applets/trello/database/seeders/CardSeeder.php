<?php

namespace Trello\Database\Seeders;

use Illuminate\Database\Seeder;
use Trello\Models\TrelloList;
use Trello\Models\Card;
use Carbon\Carbon;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lists = TrelloList::all();

        if ($lists->isEmpty()) {
            $this->command->warn('No lists found. Please run ListSeeder first.');
            return;
        }

        $statuses = ['todo', 'in_progress', 'done'];
        $priorities = ['low', 'medium', 'high'];

        $cardTemplates = [
            'Design landing page', 'Setup authentication', 'Create database schema',
            'Write API documentation', 'Implement user dashboard', 'Add payment integration',
            'Fix responsive layout', 'Optimize database queries', 'Write unit tests',
            'Deploy to staging', 'Review pull requests', 'Update dependencies',
            'Refactor legacy code', 'Add error handling', 'Improve performance',
        ];

        foreach ($lists as $list) {
            $cardCount = rand(3, 5);
            
            for ($i = 0; $i < $cardCount; $i++) {
                $title = $cardTemplates[array_rand($cardTemplates)];
                $hasDueDate = rand(0, 1);
                
                Card::create([
                    'list_id' => $list->id,
                    'title' => $title,
                    'description' => 'Sample card description for: ' . $title,
                    'due_date' => $hasDueDate ? Carbon::now()->addDays(rand(1, 30)) : null,
                    'status' => $statuses[array_rand($statuses)],
                    'priority' => $priorities[array_rand($priorities)],
                    'position' => ($i + 1) * 10,
                ]);
            }
        }

        $this->command->info('Created sample cards for each list');
    }
}
