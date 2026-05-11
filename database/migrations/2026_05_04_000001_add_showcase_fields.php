<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('studios', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('name');
            $table->string('city')->nullable()->after('address');
            $table->string('state', 2)->nullable()->after('city');
            $table->string('logo_url')->nullable()->after('description');
            $table->string('cover_url')->nullable()->after('logo_url');
            $table->boolean('featured')->default(false)->after('cover_url');
        });

        Schema::table('tattoo_artists', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('user_id');
            $table->json('specialties')->nullable()->after('experience_years');
            $table->string('avatar_url')->nullable()->after('specialties');
        });

        Schema::table('artworks', function (Blueprint $table) {
            $table->string('style')->nullable()->after('body_location');
            $table->json('tags')->nullable()->after('style');
        });
    }

    public function down(): void
    {
        Schema::table('studios', function (Blueprint $table) {
            $table->dropColumn(['slug', 'city', 'state', 'logo_url', 'cover_url', 'featured']);
        });

        Schema::table('tattoo_artists', function (Blueprint $table) {
            $table->dropColumn(['slug', 'specialties', 'avatar_url']);
        });

        Schema::table('artworks', function (Blueprint $table) {
            $table->dropColumn(['style', 'tags']);
        });
    }
};
