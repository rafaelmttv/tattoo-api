<?php

namespace App\Http\Resources\Showcase;

use App\Http\Resources\ContactResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudioShowcaseResource extends JsonResource
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
            'slug' => $this->slug,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'description' => $this->description,
            'logo_url' => $this->logo_url,
            'cover_url' => $this->cover_url,
            'featured' => $this->featured,
            'owner_name' => $this->whenLoaded('user', fn () => $this->user->name),
            'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
            'services' => StudioServiceShowcaseResource::collection($this->whenLoaded('studioServices')),
            'tattoo_artists' => ArtistShowcaseResource::collection($this->whenLoaded('tattooArtists')),
            'services_count' => $this->whenCounted('studioServices'),
            'artists_count' => $this->whenCounted('tattooArtists'),
        ];
    }
}
