<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'title' => $this->title,
            'details' => $this->details,
            'priority' => $this->priority,
            'is_completed' => (bool)$this->is_completed,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'deleted_at' => $this->deleted_at?->toDateTimeString(),
            'assignees' => \App\Http\Resources\UserResource::collection($this->whenLoaded('assignees')),
        ];
    }
}
