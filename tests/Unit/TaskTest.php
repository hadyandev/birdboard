<?php

namespace Tests\Unit;

use App\Project;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use refreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function test_it_belongs_to_a_project()
    {
        $task = factory(Task::class)->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }

    public function test_it_has_a_path()
    {
        $task = factory(Task::class)->create();

        $this->assertEquals('/projects/' . $task->project->id . '/tasks/' . $task->id, $task->path());
    }
}
