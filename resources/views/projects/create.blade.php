@extends('layouts.app')

@section('content')
    <h1>Create a Project</h1>

    <form method="POST" action="/projects">
        @csrf
        <div class="form-group">
            <label for="">Title</label>
            <input type="text" name="title" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Description</label>
            <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-dark">Create Project</button>
            <a href="/projects" class="btn btn-warning">Cancel</a>
        </div>
    </form>
@endsection