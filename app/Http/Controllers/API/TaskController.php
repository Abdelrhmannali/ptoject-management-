<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();

        if ($q = $request->query('q')) {
            $query->where('title','like', "%{$q}%")
                  ->orWhere('details','like', "%{$q}%");
        }

        if ($projectId = $request->query('project_id')) {
            $query->where('project_id', $projectId);
        }

        if (!is_null($completed = $request->query('is_completed'))) {
            $query->where('is_completed', filter_var($completed, FILTER_VALIDATE_BOOLEAN));
        }

        $sort = $request->query('sort', 'created_at');
        $dir = $request->query('dir', 'desc');
        $query->orderBy($sort, $dir);

        if ($request->boolean('with_assignees')) {
            $query->with('assignees', 'project');
        }

        if ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        $perPage = (int)$request->query('per_page', 15);
        return TaskResource::collection($query->paginate($perPage)->withQueryString());
    }

    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        $assignees = $data['assignees'] ?? [];
        unset($data['assignees']);

        $task = Task::create($data);

        if (!empty($assignees)) {
            $task->assignees()->sync($assignees);
        }

        return new TaskResource($task->load('assignees','project'));
    }

    public function show(Task $task)
    {
        return new TaskResource($task->load('assignees','project'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $data = $request->validated();
        $assignees = $data['assignees'] ?? null;
        unset($data['assignees']);

        $task->update($data);

        if (is_array($assignees)) {
            $task->assignees()->sync($assignees);
        }

        return new TaskResource($task->fresh()->load('assignees','project'));
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task soft-deleted.']);
    }

    public function restore($id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        $task->restore();
        return new TaskResource($task);
    }
  public function assignUsers(Request $request, Task $task)
{
    $data = $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $task->users()->syncWithoutDetaching([$data['user_id']]);

    return response()->json([
        'message' => 'User assigned to task successfully',
        'task' => $task->load('users')
    ]);
}

}
