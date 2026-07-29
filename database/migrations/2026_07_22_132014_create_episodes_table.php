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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('number');
            $table->string('link');
            $table->timestamps();
        });

        Schema::table('episodes', function (Blueprint $table) {
            $table->bigInteger('season_id', false, true)->after('id');
            $table->foreign('season_id', 'fk_episodes_on_season_id')->references('id')->on('seasons');

            $table->unique(['season_id', 'number'], 'unq_episodes_on_season_id_and_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
