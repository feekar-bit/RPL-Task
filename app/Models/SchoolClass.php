<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $fillable = [
        'name'
    ];


    // siswa
    public function students()
{
    return $this->hasMany(User::class, 'class_id')
                ->where('role', 'siswa');
}


    // tasks
    public function tasks()
    {
        return $this->hasMany(
            Task::class,
            'class_id'
        );
    }
}