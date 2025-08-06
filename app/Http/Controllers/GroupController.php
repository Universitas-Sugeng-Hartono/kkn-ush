<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\Angkatan;
use App\Http\Requests\GroupRequest;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        // Query yang dioptimalkan untuk menghindari duplikasi
        $groups = Kelompok::with([
            'angkatan', 
            'lokasi', 
            'dpl', 
            'mahasiswa'
        ])->groupBy('id')->get();
        
        // Hitung logbook dan absensi secara terpisah untuk menghindari duplikasi
        foreach($groups as $group) {
            $group->logbook_count = $group->logbooks()->count();
            $group->absensi_count = $group->absensi()->count();
        }
        
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $angkatan = Angkatan::all();
        $lokasi = Lokasi::all();
        $dpl = User::role('dpl')->get();
        $mahasiswa = User::role('mahasiswa')->whereNull('kelompok_id')->get();
        
        return view('groups.create', compact('angkatan', 'lokasi', 'dpl', 'mahasiswa'));
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
        $group->load(['angkatan', 'lokasi', 'dpl', 'mahasiswa', 'logbooks', 'absensi']);
        
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
        $angkatan = Angkatan::all();
        $lokasi = Lokasi::all();
        $dpl = User::role('dpl')->get();
        $mahasiswa = User::role('mahasiswa')
            ->where(function($query) use ($group) {
                $query->whereNull('kelompok_id')
                    ->orWhere('kelompok_id', $group->id);
            })
            ->get();

        return view('groups.edit', compact('group', 'angkatan', 'lokasi', 'dpl', 'mahasiswa'));
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
    public function monitoring()
    {
        $user = auth()->user();
        
        // Ambil kelompok yang dibimbing oleh dosen ini
        $groups = Kelompok::with(['angkatan', 'lokasi', 'dpl', 'mahasiswa', 'logbooks', 'absensi'])
            ->where('dpl_id', $user->id)
            ->get();

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

        return view('groups.monitoring', compact('groups', 'stats'));
    }

    public function monitoringMap()
    {
        return view('groups.monitoring_map');
    }

    public function getMonitoringData()
    {
        $user = auth()->user();
        
        $groups = Kelompok::with(['lokasi', 'mahasiswa', 'logbooks', 'absensi'])
            ->where('dpl_id', $user->id)
            ->get();

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
        if ($member->kelompok_id !== $group->id) {
            return redirect()->route('groups.show', $group)
                ->with('error', 'Mahasiswa bukan anggota kelompok ini.');
        }

        $member->update(['kelompok_id' => null]);

        return redirect()->route('groups.show', $group)
            ->with('success', 'Anggota kelompok berhasil dihapus.');
    }

    public function map()
    {
        $groups = Kelompok::with(['lokasi', 'mahasiswa', 'dpl'])->get();
        return view('groups.map', compact('groups'));
    }

    public function getMapData()
    {
        $groups = Kelompok::with(['lokasi', 'mahasiswa', 'dpl', 'logbooks', 'absensi'])->get();
        
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