<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolClassController extends Controller
{
    /**
     * Tampilkan Pusat Kendali & Daftar Kelas RPL Seluruh Angkatan
     */
    public function index(Request $request)
    {
        $selectedGrade = $request->query('grade', 'all'); // all, X, XI, XII
        $selectedStatus = $request->query('status', 'all'); // all, active, inactive

        // Query kelas dengan relasi siswa dan tugas
        $query = SchoolClass::with(['students' => function ($q) {
            $q->select('id', 'name', 'email', 'attendance_number', 'class_id', 'status');
        }])->withCount(['students', 'tasks']);

        if ($selectedGrade !== 'all' && in_array($selectedGrade, ['X', 'XI', 'XII'])) {
            $query->where('grade', $selectedGrade);
        }

        if ($selectedStatus !== 'all' && in_array($selectedStatus, ['active', 'inactive'])) {
            $query->where('status', $selectedStatus);
        }

        // Urutkan berdasarkan Tingkat (X, XI, XII) lalu Nomor Rombel
        $classes = $query
            ->orderByRaw("CASE grade WHEN 'X' THEN 1 WHEN 'XI' THEN 2 WHEN 'XII' THEN 3 ELSE 4 END")
            ->orderBy('rombel', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        // Statistik Keseluruhan
        $allClasses = SchoolClass::withCount('students')->get();
        $totalClasses = $allClasses->count();
        $totalActiveClasses = $allClasses->where('status', 'active')->count();
        $totalCapacity = $allClasses->where('status', 'active')->sum('capacity');
        $totalStudents = User::where('role', 'siswa')->count();

        // Breakdown per Angkatan (X, XI, XII)
        $grades = ['X', 'XI', 'XII'];
        $gradeStats = [];

        foreach ($grades as $g) {
            $gradeClasses = $allClasses->where('grade', $g);
            $gradeActive = $gradeClasses->where('status', 'active');
            $gradeStudentsCount = $gradeClasses->sum('students_count');
            $gradeCapacity = $gradeActive->sum('capacity');

            $gradeStats[$g] = [
                'total_rombel' => $gradeClasses->count(),
                'active_rombel' => $gradeActive->count(),
                'total_students' => $gradeStudentsCount,
                'total_capacity' => $gradeCapacity,
                'percentage' => $gradeCapacity > 0 ? min(100, (int) round(($gradeStudentsCount / $gradeCapacity) * 100)) : 0,
            ];
        }

        return view('admin.classes.index', compact(
            'classes',
            'totalClasses',
            'totalActiveClasses',
            'totalCapacity',
            'totalStudents',
            'gradeStats',
            'selectedGrade',
            'selectedStatus'
        ));
    }

    /**
     * Tambah Kelas Tunggal Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade' => 'required|in:X,XI,XII,10,11,12',
            'rombel' => 'required|integer|min:1|max:30',
            'capacity' => 'required|integer|min:1|max:100',
            'academic_year' => 'nullable|string|max:20',
            'name' => 'nullable|string|max:50',
        ]);

        // Standardisasi grade ke Romawi
        $grade = strtoupper($validated['grade']);
        if ($grade === '10') $grade = 'X';
        elseif ($grade === '11') $grade = 'XI';
        elseif ($grade === '12') $grade = 'XII';

        $rombel = (int) $validated['rombel'];
        $capacity = (int) $validated['capacity'];
        $academicYear = $validated['academic_year'] ?? date('Y') . '/' . (date('Y') + 1);

        // Jika nama kelas dikosongkan, buat otomatis sesuai standar: "{Grade} RPL {Rombel}"
        $name = !empty($validated['name']) ? trim($validated['name']) : "{$grade} RPL {$rombel}";

        // Cek apakah sudah ada kelas dengan nama yang sama
        $exists = SchoolClass::where('name', $name)->exists();
        if ($exists) {
            return back()
                ->withErrors(['name' => "Kelas \"{$name}\" sudah ada dalam sistem."])
                ->withInput();
        }

        $class = SchoolClass::create([
            'name' => $name,
            'grade' => $grade,
            'rombel' => $rombel,
            'capacity' => $capacity,
            'status' => 'active',
            'academic_year' => $academicYear,
        ]);

        return redirect()
            ->route('admin.classes.index', ['grade' => $grade])
            ->with('success', "Kelas {$class->name} berhasil dibuat dengan kapasitas {$class->capacity} siswa.");
    }

    /**
     * Atur & Generate Jumlah Rombel Sekaligus untuk Suatu Angkatan
     * Contoh: Admin input Kelas X dengan 2 rombel -> otomatis generate X RPL 1 dan X RPL 2
     */
    public function generateRombel(Request $request)
    {
        $validated = $request->validate([
            'grade' => 'required|in:X,XI,XII,10,11,12',
            'rombel_count' => 'required|integer|min:1|max:15',
            'capacity' => 'required|integer|min:5|max:100',
            'academic_year' => 'nullable|string|max:20',
        ]);

        $grade = strtoupper($validated['grade']);
        if ($grade === '10') $grade = 'X';
        elseif ($grade === '11') $grade = 'XI';
        elseif ($grade === '12') $grade = 'XII';

        $rombelCount = (int) $validated['rombel_count'];
        $capacity = (int) $validated['capacity'];
        $academicYear = $validated['academic_year'] ?? date('Y') . '/' . (date('Y') + 1);

        $createdCount = 0;
        $updatedCount = 0;

        DB::transaction(function () use ($grade, $rombelCount, $capacity, $academicYear, &$createdCount, &$updatedCount) {
            for ($i = 1; $i <= $rombelCount; $i++) {
                $name = "{$grade} RPL {$i}";

                $existing = SchoolClass::where('name', $name)->first();

                if ($existing) {
                    // Update kapasitas dan pastikan aktif
                    $existing->update([
                        'grade' => $grade,
                        'rombel' => $i,
                        'capacity' => $capacity,
                        'status' => 'active',
                        'academic_year' => $academicYear,
                    ]);
                    $updatedCount++;
                } else {
                    SchoolClass::create([
                        'name' => $name,
                        'grade' => $grade,
                        'rombel' => $i,
                        'capacity' => $capacity,
                        'status' => 'active',
                        'academic_year' => $academicYear,
                    ]);
                    $createdCount++;
                }
            }
        });

        $msg = "Berhasil mengatur rombel Angkatan {$grade} RPL: {$rombelCount} rombel disiapkan (Kapasitas: {$capacity} siswa/kelas).";
        if ($createdCount > 0) $msg .= " ({$createdCount} kelas baru dibuat";
        if ($updatedCount > 0) $msg .= ", {$updatedCount} kelas diperbarui";
        if ($createdCount > 0 || $updatedCount > 0) $msg .= ").";

        return redirect()
            ->route('admin.classes.index', ['grade' => $grade])
            ->with('success', $msg);
    }

    /**
     * Perbarui Kelas & Kapasitas (Edit)
     */
    public function update(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'grade' => 'required|in:X,XI,XII',
            'rombel' => 'required|integer|min:1|max:30',
            'capacity' => 'required|integer|min:1|max:100',
            'status' => 'required|in:active,inactive',
            'academic_year' => 'nullable|string|max:20',
        ]);

        // Cek duplikasi nama jika nama diubah
        if ($class->name !== $validated['name']) {
            $exists = SchoolClass::where('name', $validated['name'])
                ->where('id', '!=', $class->id)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['name' => "Kelas \"{$validated['name']}\" sudah ada."])
                    ->withInput();
            }
        }

        // Keamanan Kuota: Jangan izinkan kapasitas lebih kecil dari jumlah siswa terdaftar
        $currentStudentsCount = $class->students()->count();
        if ((int) $validated['capacity'] < $currentStudentsCount) {
            return back()
                ->withErrors(['capacity' => "Kapasitas tidak boleh kurang dari jumlah siswa saat ini ({$currentStudentsCount} siswa)."])
                ->withInput();
        }

        $class->update([
            'name' => trim($validated['name']),
            'grade' => $validated['grade'],
            'rombel' => (int) $validated['rombel'],
            'capacity' => (int) $validated['capacity'],
            'status' => $validated['status'],
            'academic_year' => $validated['academic_year'] ?? $class->academic_year,
        ]);

        return redirect()
            ->route('admin.classes.index', ['grade' => $class->grade])
            ->with('success', "Data kelas {$class->name} berhasil diperbarui.");
    }

    /**
     * Hapus Kelas (dengan Proteksi Keamanan Relasi)
     */
    public function destroy($id)
    {
        $class = SchoolClass::withCount(['students', 'tasks'])->findOrFail($id);

        // Jika kelas masih ada siswa terdaftar
        if ($class->students_count > 0) {
            return back()->with('error', "Kelas \"{$class->name}\" tidak dapat dihapus karena masih memiliki {$class->students_count} siswa aktif. Silakan pindahkan siswa terlebih dahulu atau ubah status kelas menjadi \"Nonaktif\".");
        }

        // Jika kelas memiliki tugas yang dibuat guru
        if ($class->tasks_count > 0) {
            return back()->with('error', "Kelas \"{$class->name}\" memiliki riwayat {$class->tasks_count} tugas terhubung. Rekomendasi: ubah status kelas menjadi \"Nonaktif\" untuk menjaga arsip tugas guru.");
        }

        $name = $class->name;
        $class->delete();

        return back()->with('success', "Kelas {$name} berhasil dihapus dari sistem.");
    }

    /**
     * Ambil data siswa dalam kelas (untuk modal / AJAX detail)
     */
    public function students($id)
    {
        $class = SchoolClass::with(['students' => function ($q) {
            $q->orderBy('attendance_number', 'asc')->orderBy('name', 'asc');
        }])->findOrFail($id);

        return response()->json([
            'class' => [
                'id' => $class->id,
                'name' => $class->name,
                'grade' => $class->grade,
                'rombel' => $class->rombel,
                'capacity' => $class->capacity,
                'students_count' => $class->students->count(),
                'remaining' => $class->remaining_capacity,
                'is_full' => $class->is_full,
                'percentage' => $class->fill_percentage,
                'status' => $class->status,
            ],
            'students' => $class->students->map(function ($s) {
                return [
                    'id' => $s->id,
                    'name' => $s->name,
                    'email' => $s->email,
                    'attendance_number' => $s->attendance_number,
                    'status' => $s->status ?? 'active',
                ];
            }),
        ]);
    }
}
