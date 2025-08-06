<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Lokasi::all();
        return view('locations.index', compact('locations'));
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

        Lokasi::create($validated);

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
        $locations = Lokasi::with(['kelompok', 'kelompok.mahasiswa'])->get();
        return view('locations.map', compact('locations'));
    }

    public function getLocations()
    {
        $locations = Lokasi::with(['kelompok', 'kelompok.mahasiswa'])->get();
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