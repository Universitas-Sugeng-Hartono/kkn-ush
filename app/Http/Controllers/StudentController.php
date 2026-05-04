<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TahunAkademik;
use App\Models\Semester;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $tahun_akademik_id = $request->query('tahun_akademik_id', $tahunAktif?->id);
        $semester_id = $request->query('semester_id', $semesterAktif?->id);
        $selected_jurusan = $request->query('jurusan');

        // Ambil mahasiswa yang dibimbing oleh DPL yang sedang login
        $studentsQuery = User::role('mahasiswa')
            ->whereHas('kelompok', function ($query) use ($tahun_akademik_id, $semester_id) {
                $query->where('dpl_id', auth()->id());

                if ($tahun_akademik_id) {
                    $query->where('tahun_akademik_id', $tahun_akademik_id);
                }
                if ($semester_id) {
                    $query->where('semester_id', $semester_id);
                }
            });

        // Ambil list jurusan yang tersedia untuk mahasiswa bimbingan DPL ini (di periode terpilih)
        $jurusanList = (clone $studentsQuery)
            ->whereNotNull('jurusan')
            ->select('jurusan', \DB::raw('count(*) as count'))
            ->groupBy('jurusan')
            ->pluck('count', 'jurusan');

        // Filter berdasarkan jurusan jika dipilih
        if ($selected_jurusan) {
            $studentsQuery->where('jurusan', $selected_jurusan);
        }

        $students = $studentsQuery->with(['kelompok', 'kelompok.lokasi'])->get();

        $tahunAkademikList = TahunAkademik::all();
        $semesterList = Semester::all();

        return view('students.index', compact(
            'students',
            'tahunAktif',
            'semesterAktif',
            'tahunAkademikList',
            'semesterList',
            'tahun_akademik_id',
            'semester_id',
            'jurusanList',
            'selected_jurusan'
        ));
    }

    public function show(User $user)
    {
        if (!$user->hasRole('mahasiswa')) {
            abort(404);
        }

        // Pastikan DPL hanya bisa melihat detail mahasiswa yang dibimbingnya
        if ($user->kelompok->dpl_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat detail mahasiswa ini.');
        }

        $user->load([
            'kelompok',
            'kelompok.lokasi',
            'logbooks' => function ($query) {
                $query->latest();
            },
            'nilai',
            'absensi' => function ($query) {
                $query->latest();
            }
        ]);

        return view('students.show', compact('user'));
    }
}
