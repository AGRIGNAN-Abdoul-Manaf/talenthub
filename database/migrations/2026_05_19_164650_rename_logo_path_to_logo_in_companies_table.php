<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('companies', 'logo_path') && !Schema::hasColumn('companies', 'logo')) {
            Schema::table('companies', function (Blueprint $table) {
                // On renomme proprement la colonne sans toucher aux données
                $table->renameColumn('logo_path', 'logo');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('companies', 'logo') && !Schema::hasColumn('companies', 'logo_path')) {
            Schema::table('companies', function (Blueprint $table) {
                // En cas de retour en arrière, on refait l'inverse
                $table->renameColumn('logo', 'logo_path');
            });
        }
    }
};