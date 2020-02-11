<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_guest_cannot_add_tasks_to_projects()
    {
        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks')
            ->assertRedirect('login');
    }

    public function test_only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();

        // jika ada project
        $project = factory('App\Project')->create();

        // lalu add task where user is not the owner of the project, expect forbidden 403
        $this->post($project->path() . '/tasks', ['body' => 'Test task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
    }

    public function test_only_the_owner_of_a_project_may_update_tasks()
    {
        $this->signIn();

        // jika ada project
        $project = factory('App\Project')->create();

        // di project tsb ada task
        $task = $project->addTask('test task');

        // lalu add task where user is not the owner of the project, expect forbidden 403
        $this->patch($task->path(), ['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    public function test_a_project_can_have_tasks()
    {
        $this->signIn();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        // another way :
        // $project = auth()->user()->projects()->create(
        //     factory('App\Project')->raw()
        // );

        // add task
        $this->post($project->path() . '/tasks', ['body' => 'Test task']);

        $this->get($project->path())
            ->assertSee('Test task');
    }

    public function test_a_task_can_be_updated()
    {

        $this->withoutExceptionHandling();
        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory('App\Project')->raw()
        );

        $task = $project->addTask('Test task');

        $this->patch($project->path() . '/tasks/' . $task->id, [
            'body' => 'changed',
            'completed' => true,
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true,
        ]);
    }

    public function test_a_task_requires_a_body()
    {
        // create a brand new user and set them authenticated
        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory('App\Project')->raw()
        );

        // 'make' ga nyimpen di db (return object), 'create' nyimpen di db (return object), 'raw' (return array)
        $attributes = factory('App\Task')->raw(['body' => '']);
        $this->post($project->path() . '/tasks', $attributes)->assertSessionHasErrors('body');
    }
}
