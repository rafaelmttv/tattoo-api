<?php

namespace App\Http\Resources\Showcase;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtworkShowcaseResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'body_location' => $this->body_location,
            'style' => $this->style,
            'tags' => $this->tags,
            'price' => $this->price,
            'creator' => $this->whenLoaded('creator', fn () => [
                'id' => $this->creator->id,
                'name' => $this->creator->user->name ?? null,
                'slug' => $this->creator->slug,
                'avatar_url' => $this->creator->avatar_url,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
