<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id', 'title', 'details', 'priority', 'is_completed'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignees()
    {
        return $this->belongsToMany(\App\Models\User::class, 'task_user')->withTimestamps();
    }
    public function users()
{
    return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
}

}
