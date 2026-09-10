<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $fillable = [
        'name',
        'grade',
        'rombel',
        'capacity',
        'status',
        'academic_year',
    ];

    /**
     * Relasi ke siswa
     */
    public function students()
    {
        return $this->hasMany(User::class, 'class_id')
                    ->where('role', 'siswa');
    }

    /**
     * Relasi ke tasks / tugas
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'class_id');
    }

    /**
     * Sisa kuota siswa
     */
    public function getRemainingCapacityAttribute()
    {
        $current = $this->students()->count();
        return max(0, (int) $this->capacity - $current);
    }

    /**
     * Cek apakah kelas sudah penuh
     */
    public function getIsFullAttribute()
    {
        return $this->students()->count() >= (int) $this->capacity;
    }

    /**
     * Persentase keterisian siswa
     */
    public function getFillPercentageAttribute()
    {
        $cap = max(1, (int) $this->capacity);
        $current = $this->students()->count();
        return min(100, (int) round(($current / $cap) * 100));
    }

    /**
     * Scope kelas aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope berdasarkan angkatan / grade
     */
    public function scopeGrade($query, $grade)
    {
        return $query->where('grade', $grade);
    }
}