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
        Schema::create('animes', function (Blueprint $table) {
            $table->id();
            $table->string('kind');
            $table->string('rating');
            $table->string('status');
            $table->string('name');
            $table->string('slug');
            $table->date('aired_at')->nullable();
            $table->date('released_at')->nullable();
            $table->smallInteger('episodes_total', false, true);
            $table->smallInteger('episodes_aired', false, true);
            $table->tinyInteger('duration', false, true);
            $table->dateTime('next_episode_at')->nullable();
            $table->timestamps();
        });

        Schema::table('animes', function (Blueprint $table) {
            $table->unique(['id', 'slug'], 'unq_animes_on_id_and_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animes');
    }
};
