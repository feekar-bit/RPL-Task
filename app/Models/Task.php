<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskSubmission;

class Task extends Model
{
    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'class_target',
        'deadline',
        'attachment',
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
}