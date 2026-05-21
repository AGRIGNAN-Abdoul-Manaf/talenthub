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
        Schema::create('applications', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $blueprint->foreignId('job_id')->constrained('job_listings')->onDelete('cascade');
            $blueprint->string('cv_path'); // Obligatoire
            $blueprint->string('cover_letter_path')->nullable(); // Optionnel
            $blueprint->text('cover_letter')->nullable(); // Texte optionnel
            $blueprint->string('status')->default('En attente');
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
