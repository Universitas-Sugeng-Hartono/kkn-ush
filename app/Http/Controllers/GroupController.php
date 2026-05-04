<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\User;
use App\Models\Lokasi;
use App\Http\Requests\GroupRequest;
use App\Models\Semester;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $tahun_akademik_id = $request->query('tahun_akademik_id', $tahunAktif?->id);
        $semester_id = $request->query('semester_id', $semesterAktif?->id);

        // Query yang dioptimalkan untuk menghindari duplikasi
        $groupsQuery = Kelompok::with([
            'tahunAkademik',
            'semester',
            'lokasi', 
            'dpl', 
            'mahasiswa'
        ])->groupBy('id');

        // Apply filters
        if ($tahun_akademik_id) {
            $groupsQuery->where('tahun_akademik_id', $tahun_akademik_id);
        }
        if ($semester_id) {
            $groupsQuery->where('semester_id', $semester_id);
        }

        $groups = $groupsQuery->get();
        
        $tahunAkademikList = TahunAkademik::all();
        $semesterList = Semester::all();
        
        // Hitung logbook dan absensi secara terpisah untuk menghindari duplikasi
        foreach($groups as $group) {
            $group->logbook_count = $group->logbooks()->count();
            $group->absensi_count = $group->absensi()->count();
        }
        
        return view('groups.index', compact('groups', 'tahunAktif', 'semesterAktif', 'tahunAkademikList', 'semesterList', 'tahun_akademik_id', 'semester_id'));
    }

    public function create()
    {
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $tahunAkademik = TahunAkademik::all();
        $semester = Semester::all();

        $lokasi = Lokasi::all();
        $dpl = User::role('dpl')->get();
        $mahasiswaQuery = User::role('mahasiswa')->whereNull('kelompok_id');
        
        if ($tahunAktif && $semesterAktif) {
            $mahasiswaQuery->where('tahun_akademik_id', $tahunAktif->id)
                           ->where('semester_id', $semesterAktif->id);
        }
        
        $mahasiswa = $mahasiswaQuery->get();
        
        return view('groups.create', compact('tahunAkademik', 'semester', 'lokasi', 'dpl', 'mahasiswa', 'tahunAktif', 'semesterAktif'));
    }

    public function store(GroupRequest $request)
    {
        $group = Kelompok::create($request->validated());

        // Assign mahasiswa ke kelompok
        if ($request->has('mahasiswa_ids')) {
            User::whereIn('id', $request->mahasiswa_ids)
                ->update(['kelompok_id' => $group->id]);
        }

        return redirect()->route('groups.show', $group)
            ->with('success', 'Kelompok KKN berhasil dibuat.');
    }

    public function show(Kelompok $group)
    {
        $group->load(['tahunAkademik', 'semester', 'lokasi', 'dpl', 'mahasiswa', 'logbooks', 'absensi']);
        
        // Statistik logbook
        $logbookStats = $group->logbooks()
            ->selectRaw('DATE(logbook.created_at) as date, COUNT(*) as count, users.kelompok_id')
            ->groupBy('date', 'users.kelompok_id')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        // Statistik absensi
        $absensiStats = $group->absensi()
            ->selectRaw('absensi.status, COUNT(*) as count, users.kelompok_id')
            ->groupBy('absensi.status', 'users.kelompok_id')
            ->get();

        return view('groups.show', compact('group', 'logbookStats', 'absensiStats'));
    }

    public function edit(Kelompok $group)
    {
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $tahunAkademik = TahunAkademik::all();
        $semester = Semester::all();

        $lokasi = Lokasi::all();
        $dpl = User::role('dpl')->get();
        $mahasiswaQuery = User::role('mahasiswa')
            ->where(function($query) use ($group) {
                // Selalu sertakan mahasiswa yang sudah menjadi anggota kelompok ini
                $query->where('kelompok_id', $group->id)
                    // ATAU mahasiswa yang belum punya kelompok DAN periodenya cocok dengan kelompok ini (atau NULL)
                    ->orWhere(function($q) use ($group) {
                        $q->whereNull('kelompok_id');
                        if ($group->tahun_akademik_id && $group->semester_id) {
                            $q->where('tahun_akademik_id', $group->tahun_akademik_id)
                              ->where('semester_id', $group->semester_id);
                        }
                    });
            });

        $mahasiswa = $mahasiswaQuery->get();

        return view('groups.edit', compact('group', 'tahunAkademik', 'semester', 'lokasi', 'dpl', 'mahasiswa', 'tahunAktif', 'semesterAktif'));
    }

    public function update(GroupRequest $request, Kelompok $group)
    {
        $group->update($request->validated());

        // Update mahasiswa kelompok
        User::where('kelompok_id', $group->id)
            ->update(['kelompok_id' => null]);

        if ($request->has('mahasiswa_ids')) {
            User::whereIn('id', $request->mahasiswa_ids)
                ->update(['kelompok_id' => $group->id]);
        }

        return redirect()->route('groups.show', $group)
            ->with('success', 'Kelompok KKN berhasil diperbarui.');
    }

    public function destroy(Kelompok $group)
    {
        // Cek apakah ada logbook atau absensi
        if ($group->logbooks()->exists() || $group->absensi()->exists()) {
            return redirect()->route('groups.index')
                ->with('error', 'Kelompok tidak dapat dihapus karena masih memiliki data logbook/absensi.');
        }

        // Reset kelompok_id mahasiswa
        User::where('kelompok_id', $group->id)
            ->update(['kelompok_id' => null]);

        $group->delete();

        return redirect()->route('groups.index')
            ->with('success', 'Kelompok KKN berhasil dihapus.');
    }

    // Monitoring methods untuk DPL
    public function monitoring(Request $request)
    {
        $user = auth()->user();

        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();
        
        $tahun_akademik_id = $request->query('tahun_akademik_id', $tahunAktif?->id);
        $semester_id = $request->query('semester_id', $semesterAktif?->id);

        // Ambil kelompok yang dibimbing oleh dosen ini
        $groupsQuery = Kelompok::with(['tahunAkademik', 'semester', 'lokasi', 'dpl', 'mahasiswa', 'logbooks', 'absensi'])
            ->where('dpl_id', $user->id);

        if ($tahun_akademik_id) {
            $groupsQuery->where('tahun_akademik_id', $tahun_akademik_id);
        }
        if ($semester_id) {
            $groupsQuery->where('semester_id', $semester_id);
        }

        $groups = $groupsQuery->get();
        $tahunAkademikList = TahunAkademik::all();
        $semesterList = Semester::all();

        // Statistik untuk dashboard
        $stats = [
            'total_mahasiswa' => $groups->sum(function($group) { return $group->mahasiswa->count(); }),
            'total_logbook' => $groups->sum(function($group) { return $group->logbooks->count(); }),
            'logbook_pending' => $groups->sum(function($group) { 
                return $group->logbooks->where('status', 'submitted')->count(); 
            }),
            'total_absensi' => $groups->sum(function($group) { return $group->absensi->count(); }),
            'absensi_pending' => $groups->sum(function($group) { 
                return $group->absensi->where('status', 'pending')->count(); 
            }),
        ];

        return view('groups.monitoring', compact('groups', 'stats', 'tahunAktif', 'semesterAktif', 'tahunAkademikList', 'semesterList', 'tahun_akademik_id', 'semester_id'));
    }

    public function monitoringMap()
    {
        return view('groups.monitoring_map');
    }

    public function getMonitoringData()
    {
        $user = auth()->user();
        
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $groupsQuery = Kelompok::with(['lokasi', 'mahasiswa', 'logbooks', 'absensi'])
            ->where('dpl_id', $user->id);

        if ($tahunAktif && $semesterAktif) {
            $groupsQuery->where('tahun_akademik_id', $tahunAktif->id)
                        ->where('semester_id', $semesterAktif->id);
        }

        $groups = $groupsQuery->get();

        $data = [];
        foreach ($groups as $group) {
            $data[] = [
                'id' => $group->id,
                'nama' => $group->nama,
                'lokasi' => $group->lokasi->nama,
                'alamat' => $group->lokasi->alamat,
                'latitude' => $group->lokasi->latitude,
                'longitude' => $group->lokasi->longitude,
                'jumlah_mahasiswa' => $group->mahasiswa->count(),
                'logbook_pending' => $group->logbooks->where('status', 'submitted')->count(),
                'absensi_pending' => $group->absensi->where('status', 'pending')->count(),
                'mahasiswa' => $group->mahasiswa->map(function($mhs) {
                    return [
                        'nama' => $mhs->name,
                        'nim' => $mhs->nim,
                        'logbook_count' => $mhs->logbooks->count(),
                        'absensi_count' => $mhs->absensi->count(),
                        'last_activity' => $mhs->logbooks->sortByDesc('created_at')->first()?->created_at?->diffForHumans()
                    ];
                })
            ];
        }

        return response()->json($data);
    }

    public function addMember(Request $request, Kelompok $group)
    {
        $request->validate([
            'mahasiswa_ids' => ['required', 'array'],
            'mahasiswa_ids.*' => ['exists:users,id']
        ]);

        User::whereIn('id', $request->mahasiswa_ids)
            ->update(['kelompok_id' => $group->id]);

        return redirect()->route('groups.show', $group)
            ->with('success', 'Anggota kelompok berhasil ditambahkan.');
    }

    public function removeMember(Kelompok $group, User $member)
    {
        if ($member->kelompok_id != $group->id) {
            return redirect()->route('groups.show', $group)
                ->with('error', 'Mahasiswa bukan anggota kelompok ini.');
        }

        $member->update(['kelompok_id' => null]);

        return redirect()->route('groups.show', $group)
            ->with('success', 'Anggota kelompok berhasil dihapus.');
    }

    public function map()
    {
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $groupsQuery = Kelompok::with(['lokasi', 'mahasiswa', 'dpl']);
        
        if ($tahunAktif && $semesterAktif) {
            $groupsQuery->where('tahun_akademik_id', $tahunAktif->id)
                        ->where('semester_id', $semesterAktif->id);
        }

        $groups = $groupsQuery->get();
        return view('groups.map', compact('groups'));
    }

    public function getMapData()
    {
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $groupsQuery = Kelompok::with(['lokasi', 'mahasiswa', 'dpl', 'logbooks', 'absensi']);
        
        if ($tahunAktif && $semesterAktif) {
            $groupsQuery->where('tahun_akademik_id', $tahunAktif->id)
                        ->where('semester_id', $semesterAktif->id);
        }

        $groups = $groupsQuery->get();
        
        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $groups->map(function ($group) {
                return [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [$group->lokasi->longitude, $group->lokasi->latitude]
                    ],
                    'properties' => [
                        'id' => $group->id,
                        'nama_kelompok' => $group->nama_kelompok,
                        'lokasi' => $group->lokasi->nama_desa . ', ' . $group->lokasi->nama_kecamatan,
                        'dpl' => $group->dpl ? $group->dpl->name : 'Belum ditentukan',
                        'total_mahasiswa' => $group->mahasiswa->count(),
                        'total_logbook' => $group->logbooks->count(),
                        'total_absensi' => $group->absensi->count()
                    ]
                ];
            })->all()
        ]);
    }
} 
