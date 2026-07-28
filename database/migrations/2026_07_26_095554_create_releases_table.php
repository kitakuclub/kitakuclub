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
        Schema::create('releases', function (Blueprint $table) {
            $table->id();
            $table->morphs('releasable', 'idx_releases_on_releasable');
            $table->string('external_id');
            $table->string('link');
            $table->timestamps();
        });

        Schema::table('releases', function (Blueprint $table) {
            $table->bigInteger('translation_id', false, true)->after('id');
            $table->foreign('translation_id', 'fk_releases_on_translation_id')->references('id')->on('translations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('releases');
    }
};
