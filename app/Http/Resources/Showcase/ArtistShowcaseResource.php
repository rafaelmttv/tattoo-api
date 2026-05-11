<?php

namespace App\Http\Resources\Showcase;

use App\Http\Resources\ContactResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistShowcaseResource extends JsonResource
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
            'name' => $this->whenLoaded('user', fn () => $this->user->name),
            'slug' => $this->slug,
            'bio' => $this->bio,
            'experience_years' => $this->experience_years,
            'specialties' => $this->specialties,
            'avatar_url' => $this->avatar_url,
            'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
            'artworks' => ArtworkShowcaseResource::collection($this->whenLoaded('artworks')),
            'services' => ArtistServiceShowcaseResource::collection($this->whenLoaded('artistServices')),
            'studios' => StudioShowcaseResource::collection($this->whenLoaded('studios')),
            'artworks_count' => $this->whenCounted('artworks'),
            'services_count' => $this->whenCounted('artistServices'),
            'studios_count' => $this->whenCounted('studios'),
        ];
    }
}
