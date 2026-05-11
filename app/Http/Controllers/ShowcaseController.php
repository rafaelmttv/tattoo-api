<?php

namespace App\Http\Controllers;

use App\Http\Resources\Showcase\ArtistShowcaseResource;
use App\Http\Resources\Showcase\ArtworkShowcaseResource;
use App\Http\Resources\Showcase\StudioShowcaseResource;
use App\Services\ShowcaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShowcaseController extends Controller
{
    public function __construct(
        private readonly ShowcaseService $showcaseService
    ) {}

    /**
     * List studios for the public showcase.
     */
    public function studios(Request $request): AnonymousResourceCollection
    {
        $studios = $this->showcaseService->studios(
            filters: $request->only(['city', 'state', 'featured', 'search']),
            perPage: $request->integer('per_page', 15)
        );

        return StudioShowcaseResource::collection($studios);
    }

    /**
     * Show a single studio with full details.
     */
    public function showStudio(int $id): StudioShowcaseResource
    {
        $studio = $this->showcaseService->showStudio($id);

        return new StudioShowcaseResource($studio);
    }

    /**
     * Get the portfolio for a studio (artworks from all its artists).
     */
    public function studioPortfolio(Request $request, int $id): AnonymousResourceCollection
    {
        $artworks = $this->showcaseService->studioPortfolio(
            studioId: $id,
            perPage: $request->integer('per_page', 15)
        );

        return ArtworkShowcaseResource::collection($artworks);
    }

    /**
     * List tattoo artists for the public showcase.
     */
    public function artists(Request $request): AnonymousResourceCollection
    {
        $artists = $this->showcaseService->artists(
            filters: $request->only(['search', 'specialty', 'min_experience']),
            perPage: $request->integer('per_page', 15)
        );

        return ArtistShowcaseResource::collection($artists);
    }

    /**
     * Show a single tattoo artist with full details.
     */
    public function showArtist(int $id): ArtistShowcaseResource
    {
        $artist = $this->showcaseService->showArtist($id);

        return new ArtistShowcaseResource($artist);
    }

    /**
     * Get the portfolio for a specific artist.
     */
    public function artistPortfolio(Request $request, int $id): AnonymousResourceCollection
    {
        $artworks = $this->showcaseService->artistPortfolio(
            artistId: $id,
            filters: $request->only(['style', 'body_location', 'min_price', 'max_price']),
            perPage: $request->integer('per_page', 15)
        );

        return ArtworkShowcaseResource::collection($artworks);
    }

    /**
     * List artworks for the public showcase.
     */
    public function artworks(Request $request): AnonymousResourceCollection
    {
        $artworks = $this->showcaseService->artworks(
            filters: $request->only(['style', 'body_location', 'search', 'min_price', 'max_price']),
            perPage: $request->integer('per_page', 15)
        );

        return ArtworkShowcaseResource::collection($artworks);
    }

    /**
     * Show a single artwork.
     */
    public function showArtwork(int $id): ArtworkShowcaseResource
    {
        $artwork = $this->showcaseService->showArtwork($id);

        return new ArtworkShowcaseResource($artwork);
    }

    /**
     * Global search across studios, artists, and artworks.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $results = $this->showcaseService->search(
            term: $request->q,
            limit: $request->integer('limit', 5)
        );

        return response()->json([
            'data' => [
                'studios' => StudioShowcaseResource::collection($results['studios']),
                'artists' => ArtistShowcaseResource::collection($results['artists']),
                'artworks' => ArtworkShowcaseResource::collection($results['artworks']),
            ],
        ]);
    }
}
