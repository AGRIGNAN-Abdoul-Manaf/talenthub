<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Rend l'ID utilisateur optionnel pour les invités
            $table->foreignId('user_id')->nullable()->change();
            
            // Informations indispensables de l'invité
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->timestamp('email_verified_at')->nullable(); // Sécurité : validation email
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->dropColumn(['guest_name', 'guest_email', 'email_verified_at']);
        });
    }
};