<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('title_es');
            $table->string('title_en');
            $table->text('description_es');
            $table->text('description_en');
            $table->string('client_es')->nullable();
            $table->string('client_en')->nullable();
            $table->string('location_es')->nullable();
            $table->string('location_en')->nullable();
            $table->integer('year')->nullable();
            $table->string('category')->nullable();
            $table->json('features_es')->nullable();
            $table->json('features_en')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
