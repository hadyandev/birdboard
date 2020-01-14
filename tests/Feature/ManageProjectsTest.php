<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    // use faker to populate data
    // refreshdatabase untuk mengembalikan keadaan db seperti semula pada testing
    use WithFaker, RefreshDatabase;

    public function test_guests_cannot_manage_projects()
    {
        $project = factory('App\Project')->create();

        // guest cannot see projects
        $this->get('/projects')->assertRedirect('login');

        // guest cannot see create project page
        $this->get('/projects/create')->assertRedirect('login');

        // guest cannot see a single project
        $this->get($project->path())->assertRedirect('login');

        // guest cannot create a project
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    public function test_a_user_can_create_a_project()
    {
        // ga nangkep exception karena kita ingin melihat exception itu sendiri
        $this->withoutExceptionHandling();

        // create a brand new user and set them authenticated
        $this->actingAs(factory('App\User')->create());

        $this->get('/projects/create')->assertStatus(200);

        // data to submit
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        // submit data with '/projects' endpoint lalu diharapkan diredirect kembali ke '/projects'
        $this->post('/projects', $attributes)->assertRedirect('/projects');

        // data yg disubmit masuk ke db dgn table projects
        $this->assertDatabaseHas('projects', $attributes);

        // melihat list project dengan '/projects' endpoint
        $this->get('/projects')->assertSee($attributes['title']);
    }

    public function test_a_user_can_view_their_project()
    {
        // being signed user
        $this->be(factory('App\User')->create());

        // ga nangkep exception karena kita ingin melihat exception itu sendiri
        $this->withoutExceptionHandling();

        // ketika ada sebuah project dimana owner_id = signed user
        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        // diharapkan dapat melihat title dan descriptionnya
        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function test_an_authenticated_user_cannot_view_the_projects_of_others()
    {
        // being signed user
        $this->be(factory('App\User')->create());

        // ketika ada sebuah project
        $project = factory('App\Project')->create();

        // secara default forbidden page
        $this->get($project->path())->assertStatus(403);
    }

    public function test_a_project_requires_a_title()
    {
        // create a brand new user and set them authenticated
        $this->actingAs(factory('App\User')->create());

        // 'make' ga nyimpen di db (return object), 'create' nyimpen di db (return object), 'raw' (return array)
        $attributes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function test_a_project_requires_a_description()
    {
        // create a brand new user and set them authenticated
        $this->actingAs(factory('App\User')->create());

        // 'make' ga nyimpen di db (return object), 'create' nyimpen di db (return object), 'raw' (return array)
        $attributes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
