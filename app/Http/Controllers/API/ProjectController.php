<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();

        // search
        if ($q = $request->query('q')) {
            $query->where(function($q2) use ($q) {
                $q2->where('name','like', "%{$q}%")
                   ->orWhere('description','like', "%{$q}%");
            });
        }

        // filter by status
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        // sort
        $sort = $request->query('sort', 'created_at');
        $dir = $request->query('dir', 'desc');
        $query->orderBy($sort, $dir);

        // include trashed?
        if ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        // eager load tasks optionally
        if ($request->boolean('with_tasks')) {
            $query->with('tasks.assignees');
        }

        $perPage = (int)$request->query('per_page', 15);
        $projects = $query->paginate($perPage)->withQueryString();

        return ProjectResource::collection($projects);
    }

    public function store(ProjectRequest $request)
    {
        $project = Project::create($request->validated());
        return new ProjectResource($project);
    }

    public function show(Project $project)
    {
        $project->load('tasks.assignees');
        return new ProjectResource($project);
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message'=>'Project soft-deleted.']);
    }

    public function restore($id)
    {
        $project = Project::withTrashed()->findOrFail($id);
        $project->restore();
        return new ProjectResource($project);
    }
}
