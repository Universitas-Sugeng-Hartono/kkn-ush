<?php

namespace App\Http\Controllers;

use App\Exports\MahasiswaTemplateExport;
use App\Imports\MahasiswaImport;
use App\Models\User;
use App\Models\Kelompok;
use App\Models\Semester;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Untuk info header (first active)
        $tahunAktif    = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        // Default ke periode aktif jika tidak ada parameter query sama sekali
        if (!$request->has('tahun_akademik_id') && !$request->has('semester_id')) {
            if ($tahunAktif && $semesterAktif) {
                $tahun_akademik_id = $tahunAktif->id;
                $semester_id = $semesterAktif->id;
            } else {
                // Tidak ada periode aktif → paksa kosong
                $tahun_akademik_id = -1;
                $semester_id = -1;
            }
        } else {
            $tahun_akademik_id = $request->query('tahun_akademik_id');
            $semester_id       = $request->query('semester_id');
        }

        $usersQuery = User::with(['roles', 'kelompok', 'tahunAkademik', 'semester']);

        // Filter tampilan user untuk periode akademik yang dipilih.
        // Mahasiswa difilter ketat berdasarkan periode.
        // User non-mahasiswa (Admin/DPL) tetap ditampilkan karena tidak terikat periode.
        if ($tahun_akademik_id || $semester_id) {
            $usersQuery->where(function ($q) use ($tahun_akademik_id, $semester_id) {
                // Tampilkan non-mahasiswa
                $q->whereDoesntHave('roles', function($rq) {
                    $rq->where('name', 'mahasiswa');
                })
                // ATAU tampilkan mahasiswa yang periodenya cocok
                ->orWhere(function ($q2) use ($tahun_akademik_id, $semester_id) {
                    $q2->whereHas('roles', function($rq) {
                            $rq->where('name', 'mahasiswa');
                        });
                    if ($tahun_akademik_id) {
                        $q2->where('tahun_akademik_id', $tahun_akademik_id);
                    }
                    if ($semester_id) {
                        $q2->where('semester_id', $semester_id);
                    }
                });
            });
        }

        $users             = $usersQuery->get();
        $tahunAkademikList = TahunAkademik::orderBy('nama', 'desc')->get();
        $semesterList      = Semester::orderBy('nama', 'asc')->get();

        return view('users.index', compact('users', 'tahunAktif', 'semesterAktif', 'tahunAkademikList', 'semesterList', 'tahun_akademik_id', 'semester_id'));
    }

    public function create()
    {
        $roles = Role::all();
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $kelompok = Kelompok::query();
        if ($tahunAktif && $semesterAktif) {
            $kelompok->where('tahun_akademik_id', $tahunAktif->id)
                     ->where('semester_id', $semesterAktif->id);
        }
        $kelompok = $kelompok->get();

        return view('users.create', compact('roles', 'kelompok'));
    }

    public function store(UserRequest $request)
    {
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $periode = [
            'tahun_akademik_id' => null,
            'semester_id' => null,
        ];

        if ($request->filled('kelompok_id')) {
            $kelompok = Kelompok::find($request->kelompok_id);
            if ($kelompok) {
                $periode['tahun_akademik_id'] = $kelompok->tahun_akademik_id;
                $periode['semester_id'] = $kelompok->semester_id;
            }
        }

        if (!$periode['tahun_akademik_id'] && $tahunAktif) {
            $periode['tahun_akademik_id'] = $tahunAktif->id;
        }
        if (!$periode['semester_id'] && $semesterAktif) {
            $periode['semester_id'] = $semesterAktif->id;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nim' => $request->nim,
            'nip' => $request->nip,
            'jurusan' => $request->jurusan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'kelompok_id' => $request->kelompok_id,
            'tahun_akademik_id' => $periode['tahun_akademik_id'],
            'semester_id' => $periode['semester_id'],
        ]);

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $user->foto_profil = $path;
            $user->save();
        }

        $user->assignRole($request->role);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'kelompok']);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $kelompok = Kelompok::query();
        if ($tahunAktif && $semesterAktif) {
            $kelompok->where('tahun_akademik_id', $tahunAktif->id)
                     ->where('semester_id', $semesterAktif->id);
        }
        $kelompok = $kelompok->get();

        return view('users.edit', compact('user', 'roles', 'kelompok'));
    }

    public function update(UserRequest $request, User $user)
    {
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $periode = [
            'tahun_akademik_id' => $user->tahun_akademik_id,
            'semester_id' => $user->semester_id,
        ];

        // Untuk mahasiswa, reset dulu agar mengikuti kelompok jika ada, atau periode aktif jika null
        if ($request->role === 'mahasiswa') {
            $periode = [
                'tahun_akademik_id' => null,
                'semester_id' => null,
            ];
        }

        if ($request->filled('kelompok_id')) {
            $kelompok = Kelompok::find($request->kelompok_id);
            if ($kelompok) {
                $periode['tahun_akademik_id'] = $kelompok->tahun_akademik_id;
                $periode['semester_id'] = $kelompok->semester_id;
            }
        }

        if (!$periode['tahun_akademik_id'] && $tahunAktif) {
            $periode['tahun_akademik_id'] = $tahunAktif->id;
        }
        if (!$periode['semester_id'] && $semesterAktif) {
            $periode['semester_id'] = $semesterAktif->id;
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nim' => $request->nim,
            'nip' => $request->nip,
            'jurusan' => $request->jurusan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'kelompok_id' => $request->kelompok_id,
            'tahun_akademik_id' => $periode['tahun_akademik_id'],
            'semester_id' => $periode['semester_id'],
        ]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto_profil);
            }
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $user->foto_profil = $path;
            $user->save();
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'File Excel wajib dipilih.',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $import = new MahasiswaImport();
        Excel::import($import, $request->file('file'));

        $message = "{$import->successCount} mahasiswa berhasil diimport.";
        if ($import->skipCount > 0) {
            $message .= " {$import->skipCount} data dilewati (duplikat).";
        }

        $response = redirect()->route('users.index')->with('success', $message);

        if (!empty($import->errors)) {
            $response = $response->with('import_errors', $import->errors);
        }

        return $response;
    }

    public function downloadTemplate()
    {
        return Excel::download(new MahasiswaTemplateExport(), 'template_import_mahasiswa.xlsx');
    }

    public function resetPassword(User $user)
    {
        $identifier = $user->nim ?? $user->nip;
        if (!$identifier) {
            return redirect()->back()->with('error', 'User tidak memiliki NIM atau NIP untuk direset passwordnya.');
        }

        $user->update([
            'password' => Hash::make($identifier)
        ]);

        return redirect()->back()->with('success', 'Password berhasil direset menjadi NIM/NIP (' . $identifier . ').');
    }

    public function bulkResetPassword(Request $request)
    {
        $userIds = json_decode($request->user_ids, true);
        if (!$userIds || !is_array($userIds)) {
            return redirect()->back()->with('error', 'Tidak ada user yang dipilih.');
        }

        $users = User::whereIn('id', $userIds)->get();
        $count = 0;

        foreach ($users as $user) {
            $identifier = $user->nim ?? $user->nip;
            if ($identifier) {
                $user->update([
                    'password' => Hash::make($identifier)
                ]);
                $count++;
            }
        }

        if ($count == 0) {
            return redirect()->back()->with('error', 'Gagal mereset. User yang dipilih mungkin tidak memiliki NIM/NIP.');
        }

        return redirect()->back()->with('success', $count . ' password user berhasil direset menjadi NIM/NIP.');
    }
}
