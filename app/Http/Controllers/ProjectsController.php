<?php

namespace App\Http\Controllers;

use App\Project;

class ProjectsController extends Controller
{
    public function index()
    {
        // display only projects that associated to signed user
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        // if authenticated user is not the project owner
        $this->authorize('update', $project);

        // udah ngebind dengan $project, gausah nyari project::find($id) lagi
        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {
        // validate
        // $attributes = request()->validate([
        //     'title' => 'required',
        //     'description' => 'required',
        //     'notes' => 'min:3',
        // ]);

        // dd($attributes);
        // owner_id adalah id dari user yg login
        // $attributes['owner_id'] = auth()->id();

        // persist
        // Project::create($attributes);

        // authenticated user bisa membuat project (refactoring)
        // $project = auth()->user()->projects()->create($attributes);

        // semua validasi sebelum store diganti dgn ini :
        $project = auth()->user()->projects()->create($this->validateRequest());

        // redirect to its project page
        return redirect($project->path());
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Project $project)
    {
        // if authenticated user is not the project owner
        // if (auth()->user()->isNot($project->owner)) {
        //     // display accessing forbidden page
        //     abort(403);
        // }

        // diganti dengan :
        $this->authorize('update', $project);

        // $attributes = request()->validate([
        //     'title' => 'required',
        //     'description' => 'required',
        //     'notes' => 'min:3',
        // ]);

        // $project->update([
        //     'notes' => request('notes'),
        // ]);

        // sama juga dengan :
        // $project->update($attributes);

        // semua validasi sebelum update diganti dgn ini :
        $project->update($this->validateRequest());

        return redirect($project->path());
    }

    /**
     * Validate the request attributes.
     *
     * @return array
     */
    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'description' => 'required',
            'notes' => 'min:3',
        ]);
    }
}
