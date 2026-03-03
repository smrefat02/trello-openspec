<?php

namespace Trello\Database\Seeders;

use Illuminate\Database\Seeder;
use Trello\Models\Board;
use App\Models\User;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->warn('No users found. Please create a user first.');
            return;
        }

        $boards = [
            [
                'user_id' => $user->id,
                'title' => 'Project Alpha',
                'description' => 'Main project board for tracking development tasks',
                'visibility' => 'private',
            ],
            [
                'user_id' => $user->id,
                'title' => 'Marketing Campaign',
                'description' => 'Track marketing initiatives and content',
                'visibility' => 'public',
            ],
            [
                'user_id' => $user->id,
                'title' => 'Personal Goals',
                'description' => 'Track personal development and learning goals',
                'visibility' => 'private',
            ],
            [
                'user_id' => $user->id,
                'title' => 'Bug Tracker',
                'description' => 'Track and prioritize bugs and issues',
                'visibility' => 'private',
            ],
            [
                'user_id' => $user->id,
                'title' => 'Feature Requests',
                'description' => 'Collect and prioritize new feature ideas',
                'visibility' => 'public',
            ],
        ];

        foreach ($boards as $boardData) {
            Board::create($boardData);
        }

        $this->command->info('Created 5 sample boards');
    }
}
