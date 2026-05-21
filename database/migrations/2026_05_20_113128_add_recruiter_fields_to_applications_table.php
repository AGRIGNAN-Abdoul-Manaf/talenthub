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
        Schema::table('applications', function (Blueprint $table) {
            $table->text('recruiter_notes')->nullable();
            $table->date('interview_date')->nullable();
            $table->time('interview_time')->nullable();
            $table->string('interview_location')->nullable();
            $table->text('interview_details')->nullable();
            $table->json('messages')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'recruiter_notes',
                'interview_date',
                'interview_time',
                'interview_location',
                'interview_details',
                'messages'
            ]);
        });
    }
};
