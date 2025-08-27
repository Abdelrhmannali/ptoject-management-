<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($q = $request->query('q')) {
            $query->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
        }

        // Sort
        $sort = $request->query('sort', 'created_at');
        $dir = $request->query('dir', 'desc');
        $query->orderBy($sort, $dir);

        // Include trashed
        if ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        $perPage = (int)$request->query('per_page', 15);
        return UserResource::collection($query->paginate($perPage)->withQueryString());
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return new UserResource($user);
    }

    public function show(User $user)
    {
        $user->load('tasks.project');
        return new UserResource($user);
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);
        return new UserResource($user->fresh());
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User soft-deleted.']);
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return new UserResource($user);
    }

    public function tasks(User $user, Request $request)
    {
        $tasks = $user->tasks()->with('project')->paginate($request->query('per_page', 15));
        return \App\Http\Resources\TaskResource::collection($tasks);
    }
}
