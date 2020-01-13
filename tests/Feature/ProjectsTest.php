<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    // use faker to populate data
    // refreshdatabase untuk mengembalikan keadaan db seperti semula pada testing
    use WithFaker, RefreshDatabase;

    public function test_a_user_can_create_a_project()
    {
        // ga nangkep exception karena kita ingin melihat exception itu sendiri
        $this->withoutExceptionHandling();

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

    public function test_a_project_requires_a_title()
    {
        // 'make' ga nyimpen di db (return object), 'create' nyimpen di db (return object), 'raw' (return array)
        $attributes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function test_a_project_requires_a_description()
    {
        // 'make' ga nyimpen di db (return object), 'create' nyimpen di db (return object), 'raw' (return array)
        $attributes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
