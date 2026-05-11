<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TattooArtistResource extends JsonResource
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
            'bio' => $this->bio,
            'experience_years' => $this->experience_years,
            'user' => new UserResource($this->whenLoaded('user')),
            'artworks' => ArtworkResource::collection($this->whenLoaded('artworks')),
            'services' => ArtistServiceResource::collection($this->whenLoaded('artistServices')),
            'studios' => StudioResource::collection($this->whenLoaded('studios')),
            'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
            'artworks_count' => $this->whenCounted('artworks'),
            'services_count' => $this->whenCounted('artistServices'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
