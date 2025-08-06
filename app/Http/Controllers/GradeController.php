<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Nilai;
use Illuminate\Http\Request;
use App\Models\Kelompok; // Added this import

class GradeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Jika dosen, hanya tampilkan penilaian mahasiswa bimbingannya
        if ($user->hasRole('dpl')) {
            $groups = Kelompok::where('dpl_id', $user->id)->pluck('id');
            $grades = Nilai::with(['user', 'user.kelompok'])
                ->whereIn('kelompok_id', $groups)
                ->latest()
                ->get()
                // Filter ekstra: pastikan user.kelompok.dpl_id = user->id
                ->filter(function($grade) use ($user) {
                    return $grade->user && $grade->user->kelompok && $grade->user->kelompok->dpl_id == $user->id;
                });
        } else {
            // Admin bisa lihat semua
            $grades = Nilai::with(['user', 'user.kelompok'])
                ->latest()
                ->get();
        }
        
        // Data untuk filter
        $groups = Kelompok::all();
            
        return view('grades.index', compact('grades', 'groups'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Jika dosen, hanya tampilkan mahasiswa bimbingannya
        if ($user->hasRole('dpl')) {
            $groups = Kelompok::where('dpl_id', $user->id)->pluck('id');
            $students = User::role('mahasiswa')
                ->whereIn('kelompok_id', $groups)
                ->get();
        } else {
            // Admin bisa pilih semua mahasiswa
            $students = User::role('mahasiswa')->get();
        }
        
        return view('grades.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kelompok_id' => 'nullable|exists:kelompok,id',
            // Tahap Pembekalan (10%)
            'nilai_kehadiran_pembekalan' => 'required|numeric|min:0|max:100',
            'nilai_sikap_pembekalan' => 'required|numeric|min:0|max:100',
            // Pelaksanaan (60%)
            'nilai_kehadiran_lokasi' => 'required|numeric|min:0|max:100',
            'nilai_sikap_pelaksanaan' => 'required|numeric|min:0|max:100',
            'nilai_keterlibatan_kegiatan' => 'required|numeric|min:0|max:100',
            'nilai_relevansi_program' => 'required|numeric|min:0|max:100',
            'nilai_keberhasilan_program' => 'required|numeric|min:0|max:100',
            // Laporan KKN Tematik (30%)
            'nilai_sistematika_laporan' => 'required|numeric|min:0|max:100',
            'nilai_konten_medsos' => 'required|numeric|min:0|max:100',
            'nilai_bahasa' => 'required|numeric|min:0|max:100',
            'nilai_analisis' => 'required|numeric|min:0|max:100',
            'nilai_ketepatan_waktu' => 'required|numeric|min:0|max:100',
            'nilai_produk_teknologi' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string'
        ]);

        // Get kelompok_id from user if not provided
        if (!$validated['kelompok_id']) {
            $user = User::find($validated['user_id']);
            $validated['kelompok_id'] = $user->kelompok_id;
        }

        $nilai = Nilai::create([
            'user_id' => $validated['user_id'],
            'kelompok_id' => $validated['kelompok_id'],
            // Tahap Pembekalan (10%)
            'nilai_kehadiran_pembekalan' => $validated['nilai_kehadiran_pembekalan'],
            'nilai_sikap_pembekalan' => $validated['nilai_sikap_pembekalan'],
            // Pelaksanaan (60%)
            'nilai_kehadiran_lokasi' => $validated['nilai_kehadiran_lokasi'],
            'nilai_sikap_pelaksanaan' => $validated['nilai_sikap_pelaksanaan'],
            'nilai_keterlibatan_kegiatan' => $validated['nilai_keterlibatan_kegiatan'],
            'nilai_relevansi_program' => $validated['nilai_relevansi_program'],
            'nilai_keberhasilan_program' => $validated['nilai_keberhasilan_program'],
            // Laporan KKN Tematik (30%)
            'nilai_sistematika_laporan' => $validated['nilai_sistematika_laporan'],
            'nilai_konten_medsos' => $validated['nilai_konten_medsos'],
            'nilai_bahasa' => $validated['nilai_bahasa'],
            'nilai_analisis' => $validated['nilai_analisis'],
            'nilai_ketepatan_waktu' => $validated['nilai_ketepatan_waktu'],
            'nilai_produk_teknologi' => $validated['nilai_produk_teknologi'],
            'catatan' => $validated['catatan'],
            'dpl_id' => auth()->id()
        ]);

        return redirect()->route('grades.index')
            ->with('success', 'Penilaian berhasil ditambahkan');
    }

    public function show(Nilai $grade)
    {
        return view('grades.show', compact('grade'));
    }

    public function edit(Nilai $grade)
    {
        $students = User::role('mahasiswa')->get();
        return view('grades.edit', compact('grade', 'students'));
    }

    public function update(Request $request, Nilai $grade)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            // Tahap Pembekalan (10%)
            'nilai_kehadiran_pembekalan' => 'required|numeric|min:0|max:100',
            'nilai_sikap_pembekalan' => 'required|numeric|min:0|max:100',
            // Pelaksanaan (60%)
            'nilai_kehadiran_lokasi' => 'required|numeric|min:0|max:100',
            'nilai_sikap_pelaksanaan' => 'required|numeric|min:0|max:100',
            'nilai_keterlibatan_kegiatan' => 'required|numeric|min:0|max:100',
            'nilai_relevansi_program' => 'required|numeric|min:0|max:100',
            'nilai_keberhasilan_program' => 'required|numeric|min:0|max:100',
            // Laporan KKN Tematik (30%)
            'nilai_sistematika_laporan' => 'required|numeric|min:0|max:100',
            'nilai_konten_medsos' => 'required|numeric|min:0|max:100',
            'nilai_bahasa' => 'required|numeric|min:0|max:100',
            'nilai_analisis' => 'required|numeric|min:0|max:100',
            'nilai_ketepatan_waktu' => 'required|numeric|min:0|max:100',
            'nilai_produk_teknologi' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string'
        ]);

        $grade->update($validated);

        return redirect()->route('grades.index')
            ->with('success', 'Nilai berhasil diperbarui');
    }

    public function destroy(Nilai $grade)
    {
        $grade->delete();

        return redirect()->route('grades.index')
            ->with('success', 'Nilai berhasil dihapus');
    }
} 