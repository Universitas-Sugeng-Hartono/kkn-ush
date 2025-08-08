<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        // Ambil mahasiswa yang dibimbing oleh DPL yang sedang login
        $students = User::role('mahasiswa')
            ->whereHas('kelompok', function($query) {
                $query->where('dpl_id', auth()->id());
            })
            ->with(['kelompok', 'kelompok.lokasi'])
            ->get();
            
        return view('students.index', compact('students'));
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
            'logbooks' => function($query) {
                $query->latest();
            },
            'nilai',
            'absensi' => function($query) {
                $query->latest();
            }
        ]);

        return view('students.show', compact('user'));
    }
} 