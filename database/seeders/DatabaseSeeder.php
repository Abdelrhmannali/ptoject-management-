<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory(5)->create();

        Project::factory(8)->create()->each(function ($project) {
            $tasks = Task::factory(rand(2,6))->make();
            $project->tasks()->saveMany($tasks);

            // attach random users to tasks
            $users = \App\Models\User::inRandomOrder()->take(3)->pluck('id');
            foreach ($project->tasks as $task) {
                $task->assignees()->attach($users->random(rand(1, $users->count())));
            }
        });
    }
}
