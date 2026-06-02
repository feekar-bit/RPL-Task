<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskSubmission;
use App\Models\SchoolClass;

class Task extends Model
{
    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'deadline',
        'attachment',
        'class_id',
    ];

    // relasi guru
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}