<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->text('url')->nullable();
            $table->text('referrer')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('visited_at');
            $table->timestamps();
            $table->index('visited_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitas');
    }
};
