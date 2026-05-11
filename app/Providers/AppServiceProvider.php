<?php

namespace App\Providers;

use App\Models\ArtistService;
use App\Models\Artwork;
use App\Models\Customer;
use App\Models\Studio;
use App\Models\StudioService;
use App\Models\TattooArtist;
use App\Policies\ArtistServicePolicy;
use App\Policies\ArtworkPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\StudioPolicy;
use App\Policies\StudioServicePolicy;
use App\Policies\TattooArtistPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Studio::class, StudioPolicy::class);
        Gate::policy(TattooArtist::class, TattooArtistPolicy::class);
        Gate::policy(Artwork::class, ArtworkPolicy::class);
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(ArtistService::class, ArtistServicePolicy::class);
        Gate::policy(StudioService::class, StudioServicePolicy::class);
    }
}
