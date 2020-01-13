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

    public function store()
    {
        // validate
        // persist

        Project::create(request(['title', 'description']));
        // redirect
        return redirect('/projects');
    }
}
