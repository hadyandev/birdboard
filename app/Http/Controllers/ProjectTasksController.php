<?php

namespace App\Http\Controllers;

use App\Project;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        // validasi jika user bukan owner dari project
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }

        request()->validate(['body' => 'required']);

        $project->addTask(request('body'));

        return redirect($project->path());
    }
}
