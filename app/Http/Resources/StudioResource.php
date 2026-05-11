<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudioResource extends JsonResource
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
            'address' => $this->address,
            'description' => $this->description,
            'user' => new UserResource($this->whenLoaded('user')),
            'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
            'services' => StudioServiceResource::collection($this->whenLoaded('studioServices')),
            'tattoo_artists' => TattooArtistResource::collection($this->whenLoaded('tattooArtists')),
            'services_count' => $this->whenCounted('studioServices'),
            'artists_count' => $this->whenCounted('tattooArtists'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
