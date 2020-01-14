<?php

namespace App\Http\Controllers;

use App\Project;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        // udah ngebind dengan $project, gausah nyari project::find($id) lagi
        return view('projects.show', compact('project'));
    }

    public function store()
    {
        // validate
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        // owner_id adalah id dari user yg login
        // $attributes['owner_id'] = auth()->id();

        // persist
        // Project::create($attributes);

        // authenticated user bisa membuat project (refactoring)
        auth()->user()->projects()->create($attributes);

        // redirect
        return redirect('/projects');
    }
}
