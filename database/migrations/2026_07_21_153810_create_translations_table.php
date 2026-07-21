<?php

declare(strict_types=1);

use App\Enums\EntryLocale as Locale;
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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->string('external_id');
            $table->string('kind');
            $table->char('locale', 2)->default(Locale::RU);
            $table->timestamps();
        });

        Schema::table('translations', function (Blueprint $table) {
            $table->bigInteger('funteam_id', false, true)->after('id');
            $table->foreign('funteam_id', 'fk_translations_on_funteam_id')->references('id')->on('funteams');

            $table->unique(['source', 'external_id'], 'unq_translations_on_source_and_external_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
