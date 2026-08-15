<?php

declare(strict_types=1);

use App\Enums\EntrySeason as AnimeSeason;
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
        Schema::table('animes', function (Blueprint $table) {
            $table->year('aired_year')->nullable()->after('aired_at');
            $table->string('aired_season', 8)->default(AnimeSeason::UNKNOWN)->after('aired_year');

            $table->index(['aired_year', 'aired_season'], 'idx_animes_on_aired_year_and_aired_season');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animes', function (Blueprint $table) {
            $table->dropIndex('idx_animes_on_aired_year_and_aired_season');

            $table->dropColumn('aired_year');
            $table->dropColumn('aired_season');
        });
    }
};
