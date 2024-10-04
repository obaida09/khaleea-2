<?php

namespace App\Http\Resources\FrontEnd;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'user' => $this->user->name,
            'parent_id' => $this->parent_id,
            'replies'  => CommentResource::collection($this->whenLoaded('replies')),
            'created_at' => $this->created_at->toDateTimeString(),
            'user'     => new UserResource($this->whenLoaded('user')),
        ];
    }
}
