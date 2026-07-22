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
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->integer('number', false, true)->default(0);
            $table->string('link');
            $table->timestamps();
        });

        Schema::table('seasons', function (Blueprint $table) {
            $table->bigInteger('translation_id', false, true)->after('id');
            $table->foreign('translation_id', 'fk_seasons_on_translation_id')->references('id')->on('translations');

            $table->unique(['translation_id', 'number'], 'unq_seasons_on_translation_id_and_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
