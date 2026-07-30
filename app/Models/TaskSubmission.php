<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    protected $fillable = [

        'task_id',
        'student_id',

        'submission_note',
        'submission_link',
        'submission_file',

        'progress',
        'score',
        'feedback',

        'teacher_feedback',

        'grade',
    ];

    // relasi task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // relasi siswa
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}