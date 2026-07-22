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
        Schema::create('translation_episode', function (Blueprint $table) {
            $table->bigInteger('episode_id', false, true);
            $table->bigInteger('translation_id', false, true);
        });

        Schema::table('translation_episode', function (Blueprint $table) {
            $table->foreign('episode_id', 'fk_translation_episode_on_episode_id')->references('id')->on('episodes');
            $table->foreign('translation_id', 'fk_translation_episode_on_translation_id')->references('id')->on('translations');

            $table->index('episode_id', 'idx_translation_episode_on_episode_id');

            $table->unique(['translation_id', 'episode_id'], 'unq_translation_episode_on_translation_id_and_episode_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_episode');
    }
};
