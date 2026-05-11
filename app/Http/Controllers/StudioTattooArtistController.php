<?php

namespace App\Http\Controllers;

use App\Http\Resources\TattooArtistResource;
use App\Services\StudioTattooArtistService;
use App\Models\Studio;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StudioTattooArtistController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly StudioTattooArtistService $studioTattooArtistService
    ) {}

    /**
     * List all tattoo artists for a studio.
     */
    public function index(int $studioId): AnonymousResourceCollection
    {
        $artists = $this->studioTattooArtistService->listForStudio($studioId);

        return TattooArtistResource::collection($artists);
    }

    /**
     * Associate a tattoo artist with a studio.
     */
    public function store(Request $request, int $studioId): JsonResponse
    {
        $studio = Studio::where('user_id', $request->user()->id)->findOrFail($studioId);

        $request->validate([
            'tattoo_artist_id' => 'required|exists:tattoo_artists,id',
        ]);

        $this->studioTattooArtistService->associate(
            $studio,
            $request->tattoo_artist_id
        );

        return response()->json([
            'message' => 'Tattoo artist associated successfully.',
        ], 201);
    }

    /**
     * Disassociate a tattoo artist from a studio.
     */
    public function destroy(Request $request, int $studioId, int $tattooArtistId): JsonResponse
    {
        $studio = Studio::where('user_id', $request->user()->id)->findOrFail($studioId);

        $this->studioTattooArtistService->disassociate($studio, $tattooArtistId);

        return $this->noContentResponse();
    }
}