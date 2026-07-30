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
        Schema::create('task_submissions', function (Blueprint $table) {

            $table->id();

            // relasi
            $table->foreignId('task_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('student_id')
                ->constrained('users')
                ->onDelete('cascade');

            // isi submission
            $table->text('submission_note')->nullable();

            $table->string('submission_link')->nullable();

            $table->string('submission_file')->nullable();

            // progress tracker
            $table->integer('progress')
                ->default(0);

            // feedback guru
            $table->text('teacher_feedback')
                ->nullable();

            // nilai
            $table->integer('grade')
                ->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
    }
};