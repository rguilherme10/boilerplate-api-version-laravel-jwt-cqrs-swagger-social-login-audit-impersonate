<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('social_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('oauth_type');
            $table->string('oauth_id')->unique();
            $table->text('oauth_token')->nullable();
            $table->string('oauth_avatar')->nullable();
            $table->timestamps();

            // Relacionamento com tabela users, se existir
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_users');
    }
};