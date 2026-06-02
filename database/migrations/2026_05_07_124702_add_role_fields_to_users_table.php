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
        Schema::table('users', function (Blueprint $table) {

            $table->enum('role', ['admin', 'guru', 'siswa'])
                ->default('siswa');

            $table->string('photo')->nullable();

            // untuk siswa
            $table->string('class')->nullable();
            $table->string('attendance_number')->nullable();

            // untuk guru
            $table->string('teacher_id')->nullable();

            // approval guru
            $table->boolean('is_approved')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'role',
                'photo',
                'class',
                'attendance_number',
                'teacher_id',
                'is_approved'
            ]);

        });
    }
};