<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class TahunAkademikController extends Controller
{
    public function index()
    {
        $tahunAkademikList = TahunAkademik::orderBy('nama', 'desc')->get();
        $semesterList = Semester::orderBy('nama', 'asc')->get();
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        return view('tahun-akademik.index', compact(
            'tahunAkademikList',
            'semesterList',
            'tahunAktif',
            'semesterAktif'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:20|unique:tahun_akademik,nama',
        ], [
            'nama.required' => 'Nama tahun akademik wajib diisi.',
            'nama.unique' => 'Tahun akademik tersebut sudah ada.',
        ]);

        TahunAkademik::create([
            'nama' => $request->nama,
            'is_aktif' => false,
        ]);

        return redirect()->route('tahun-akademik.index')
            ->with('success', 'Tahun Akademik berhasil ditambahkan.');
    }

    public function update(Request $request, TahunAkademik $tahunAkademik)
    {
        $request->validate([
            'nama' => 'required|string|max:20|unique:tahun_akademik,nama,' . $tahunAkademik->id,
        ], [
            'nama.required' => 'Nama tahun akademik wajib diisi.',
            'nama.unique' => 'Tahun akademik tersebut sudah ada.',
        ]);

        $tahunAkademik->update(['nama' => $request->nama]);

        return redirect()->route('tahun-akademik.index')
            ->with('success', 'Tahun Akademik berhasil diperbarui.');
    }

    public function destroy(TahunAkademik $tahunAkademik)
    {
        if ($tahunAkademik->is_aktif) {
            return redirect()->route('tahun-akademik.index')
                ->with('error', 'Tidak dapat menghapus tahun akademik yang sedang aktif.');
        }

        if ($tahunAkademik->kelompok()->count() > 0 || $tahunAkademik->mahasiswa()->count() > 0) {
            return redirect()->route('tahun-akademik.index')
                ->with('error', 'Tidak dapat menghapus tahun akademik yang sudah memiliki kelompok atau mahasiswa.');
        }

        $tahunAkademik->delete();

        return redirect()->route('tahun-akademik.index')
            ->with('success', 'Tahun Akademik berhasil dihapus.');
    }

    public function setAktif(TahunAkademik $tahunAkademik)
    {
        $tahunAkademik->setAsAktif();
        return redirect()->route('tahun-akademik.index')
            ->with('success', "Tahun Akademik {$tahunAkademik->nama} berhasil diaktifkan.");
    }

    public function setNonaktif(TahunAkademik $tahunAkademik)
    {
        $tahunAkademik->setAsNonaktif();
        return redirect()->route('tahun-akademik.index')
            ->with('success', "Tahun Akademik {$tahunAkademik->nama} berhasil dinonaktifkan.");
    }

    public function storeSemester(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:semester,nama',
        ], [
            'nama.required' => 'Nama semester wajib diisi.',
            'nama.unique' => 'Semester tersebut sudah ada.',
        ]);

        Semester::create([
            'nama' => $request->nama,
            'is_aktif' => false,
        ]);

        return redirect()->route('tahun-akademik.index')
            ->with('success', 'Semester berhasil ditambahkan.');
    }

    public function updateSemester(Request $request, Semester $semester)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:semester,nama,' . $semester->id,
        ], [
            'nama.required' => 'Nama semester wajib diisi.',
            'nama.unique' => 'Semester tersebut sudah ada.',
        ]);

        $semester->update(['nama' => $request->nama]);

        return redirect()->route('tahun-akademik.index')
            ->with('success', 'Semester berhasil diperbarui.');
    }

    public function destroySemester(Semester $semester)
    {
        if ($semester->is_aktif) {
            return redirect()->route('tahun-akademik.index')
                ->with('error', 'Tidak dapat menghapus semester yang sedang aktif.');
        }

        if ($semester->kelompok()->count() > 0 || $semester->mahasiswa()->count() > 0) {
            return redirect()->route('tahun-akademik.index')
                ->with('error', 'Tidak dapat menghapus semester yang sudah memiliki kelompok atau mahasiswa.');
        }

        $semester->delete();

        return redirect()->route('tahun-akademik.index')
            ->with('success', 'Semester berhasil dihapus.');
    }

    public function setSemesterAktif(Semester $semester)
    {
        $semester->setAsAktif();
        return redirect()->route('tahun-akademik.index')
            ->with('success', "Semester {$semester->nama} berhasil diaktifkan.");
    }

    public function setSemesterNonaktif(Semester $semester)
    {
        $semester->setAsNonaktif();
        return redirect()->route('tahun-akademik.index')
            ->with('success', "Semester {$semester->nama} berhasil dinonaktifkan.");
    }

}

