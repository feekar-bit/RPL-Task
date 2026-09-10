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
        Schema::table('school_classes', function (Blueprint $table) {
            $table->string('grade', 10)->nullable()->after('name'); // X, XI, XII
            $table->unsignedTinyInteger('rombel')->nullable()->after('grade'); // 1, 2, 3...
            $table->unsignedSmallInteger('capacity')->default(36)->after('rombel'); // batas maksimal siswa
            $table->string('status', 20)->default('active')->after('capacity'); // active / inactive
            $table->string('academic_year', 20)->nullable()->after('status'); // misal: 2025/2026
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->dropColumn(['grade', 'rombel', 'capacity', 'status', 'academic_year']);
        });
    }
};
