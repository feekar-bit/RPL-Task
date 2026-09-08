<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskSubmission;

class Task extends Model
{
    protected $fillable = [
        'teacher_id',
        'class_id',
        'title',
        'description',
        'deadline',
        'attachment',
    ];

    // relasi guru
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // relasi kelas
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }
}