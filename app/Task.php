<?php

namespace App;

use App\Project;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    // merelasikan perubahn dengan parent/objek yg diinginkan. ketika task bertambah/berubah, last_updated pada project juga berubah
    protected $touches = ['project'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function path()
    {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }
}
