<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('episode_release', function (Blueprint $table) {
            $table->bigInteger('release_id', false, true);
            $table->bigInteger('episode_id', false, true);
        });

        Schema::table('episode_release', function (Blueprint $table) {
            $table->foreign('release_id', 'fk_episode_release_on_release_id')->references('id')->on('releases');
            $table->foreign('episode_id', 'fk_episode_release_on_episode_id')->references('id')->on('episodes');

            $table->unique(['release_id', 'episode_id'], 'unq_episode_release_on_release_id_and_episode_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episode_release');
    }
};
