<?php

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 32)->unique('unq_users_on_username');
            $table->string('nickname', 128);
            $table->string('email')->nullable()->unique('unq_users_on_email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->dateTime('last_seen_at')->default(now());
            $table->integer('views_count')->unsigned()->default(0);
            $table->string('autologin_token', 64)->unique('unq_users_on_autologin_token');
            $table->rememberToken();
            $table->binary('registration_ip_hash', 32)->integer('idx_users_on_registration_ip_hash');
            $table->char('registration_country', 2);
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
