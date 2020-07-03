<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'total_members' => $this->members->count(),
      'slug' => $this->slug,
      'designs' => DesignResource::collection($this->designs),
      'owner' => new UserResource($this->owner),
      'member' => UserResource::collection($this->members),
      'created_at_dates' => [
        'created_at_human' => $this->created_at->diffForHumans(),
        'created_at' => $this->created_at
      ],
    ];
  }
}
