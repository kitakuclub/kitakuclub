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
        Schema::table('seasons', function (Blueprint $table) {
            $table->bigInteger('release_id', false, true)->after('id');
            $table->foreign('release_id', 'fk_seasons_on_release_id')->references('id')->on('releases');

            $table->unique(['release_id', 'number'], 'unq_seasons_on_release_id_and_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->dropForeign('fk_seasons_on_release_id');
        });
    }
};
