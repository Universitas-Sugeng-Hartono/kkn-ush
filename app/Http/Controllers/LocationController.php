<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\Lokasi;
use App\Models\Semester;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif    = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        // Default ke periode aktif jika tidak ada parameter query sama sekali
        if (!$request->has('tahun_akademik_id') && !$request->has('semester_id') && $tahunAktif && $semesterAktif) {
            $tahun_akademik_id = $tahunAktif->id;
            $semester_id = $semesterAktif->id;
        } else {
            $tahun_akademik_id = $request->query('tahun_akademik_id');
            $semester_id       = $request->query('semester_id');
        }

        $tahunAkademikList = TahunAkademik::where('is_aktif', true)->orderBy('nama', 'desc')->get();
        $semesterList      = Semester::where('is_aktif', true)->orderBy('nama', 'asc')->get();

        // Ambil lokasi yang sesuai dengan periode yang dipilih, beserta kelompoknya
        $locationsQuery = Lokasi::query();
        if ($tahun_akademik_id) {
            $locationsQuery->where('tahun_akademik_id', $tahun_akademik_id);
        }
        if ($semester_id) {
            $locationsQuery->where('semester_id', $semester_id);
        }

        $locations = $locationsQuery->with(['kelompok' => function ($query) use ($tahun_akademik_id, $semester_id) {
            if ($tahun_akademik_id) {
                $query->where('tahun_akademik_id', $tahun_akademik_id);
            }
            if ($semester_id) {
                $query->where('semester_id', $semester_id);
            }
            $query->with('mahasiswa');
        }])->get();

        return view('locations.index', compact(
            'locations',
            'tahunAktif',
            'semesterAktif',
            'tahunAkademikList',
            'semesterList',
            'tahun_akademik_id',
            'semester_id'
        ));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_desa' => 'required|string|max:255',
            'nama_kecamatan' => 'required|string|max:255',
            'nama_kabupaten' => 'required|string|max:255',
            'nama_provinsi' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'deskripsi' => 'nullable|string'
        ]);

        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        Lokasi::create(array_merge($validated, [
            'tahun_akademik_id' => $tahunAktif?->id,
            'semester_id' => $semesterAktif?->id
        ]));

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil ditambahkan');
    }

    public function show(Lokasi $location)
    {
        $location->load(['kelompok.mahasiswa', 'kelompok.dpl', 'kelompok.logbooks']);
        return view('locations.show', compact('location'));
    }

    public function edit(Lokasi $location)
    {
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Lokasi $location)
    {
        $validated = $request->validate([
            'nama_desa' => 'required|string|max:255',
            'nama_kecamatan' => 'required|string|max:255',
            'nama_kabupaten' => 'required|string|max:255',
            'nama_provinsi' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'deskripsi' => 'nullable|string'
        ]);

        $location->update($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil diperbarui');
    }

    public function destroy(Lokasi $location)
    {
        $location->delete();

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil dihapus');
    }

    public function map()
    {
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $locationsQuery = Lokasi::query();
        if ($tahunAktif) {
            $locationsQuery->where('tahun_akademik_id', $tahunAktif->id);
        }
        if ($semesterAktif) {
            $locationsQuery->where('semester_id', $semesterAktif->id);
        }

        $locations = $locationsQuery->with(['kelompok', 'kelompok.mahasiswa'])->get();
        return view('locations.map', compact('locations'));
    }

    public function getLocations()
    {
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $locationsQuery = Lokasi::query();
        if ($tahunAktif) {
            $locationsQuery->where('tahun_akademik_id', $tahunAktif->id);
        }
        if ($semesterAktif) {
            $locationsQuery->where('semester_id', $semesterAktif->id);
        }

        $locations = $locationsQuery->with(['kelompok', 'kelompok.mahasiswa'])->get();
        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $locations->map(function ($location) {
                return [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [$location->longitude, $location->latitude]
                    ],
                    'properties' => [
                        'id' => $location->id,
                        'nama_desa' => $location->nama_desa,
                        'nama_kecamatan' => $location->nama_kecamatan,
                        'nama_kabupaten' => $location->nama_kabupaten,
                        'nama_provinsi' => $location->nama_provinsi,
                        'total_kelompok' => $location->kelompok->count(),
                        'total_mahasiswa' => $location->kelompok->sum(function ($kelompok) {
                            return $kelompok->mahasiswa->count();
                        })
                    ]
                ];
            })->all()
        ]);
    }
} 