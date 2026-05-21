<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->unique();
            $table->string('location');
            $table->string('website')->nullable();
            $table->text('description')->nullable();
           $table->string('logo')->nullable(); //
           $table->timestamps(); // <--- CETTE LIGNE EST CRUCIALE
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};