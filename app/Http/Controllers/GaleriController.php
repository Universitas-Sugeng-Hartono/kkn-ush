<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GaleriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Galeri::class, 'galeri');
    }

    public function index()
    {
        try {
            $galeri = Galeri::with('user')
                ->orderBy('urutan')
                ->orderBy('created_at', 'desc')
                ->get();
                
            return view('galeri.index', compact('galeri'));
        } catch (\Exception $e) {
            Log::error('Error in GaleriController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data galeri.');
        }
    }

    public function create()
    {
        return view('galeri.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'deskripsi' => 'nullable|string|max:1000',
                'aktif' => 'boolean',
                'urutan' => 'nullable|integer|min:0'
            ], [
                'judul.required' => 'Judul foto wajib diisi.',
                'judul.max' => 'Judul foto maksimal 255 karakter.',
                'gambar.required' => 'Foto wajib diupload.',
                'gambar.image' => 'File harus berupa gambar.',
                'gambar.mimes' => 'Format gambar harus JPEG, PNG, atau JPG.',
                'gambar.max' => 'Ukuran gambar maksimal 2MB.',
                'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
                'urutan.integer' => 'Urutan harus berupa angka.',
                'urutan.min' => 'Urutan minimal 0.'
            ]);

            $gambar = $request->file('gambar');
            $path = $gambar->store('galeri', 'public');

            Galeri::create([
                'judul' => $request->judul,
                'gambar' => $path,
                'deskripsi' => $request->deskripsi,
                'aktif' => $request->boolean('aktif', true),
                'urutan' => $request->urutan ?? 0,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('galeri.index')
                ->with('success', 'Foto berhasil ditambahkan ke galeri.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error in GaleriController@store: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan foto.')
                ->withInput();
        }
    }

    public function edit(Galeri $galeri)
    {
        try {
            return view('galeri.edit', compact('galeri'));
        } catch (\Exception $e) {
            Log::error('Error in GaleriController@edit: ' . $e->getMessage());
            return redirect()->route('galeri.index')
                ->with('error', 'Terjadi kesalahan saat memuat halaman edit.');
        }
    }

    public function update(Request $request, Galeri $galeri)
    {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'deskripsi' => 'nullable|string|max:1000',
                'aktif' => 'boolean',
                'urutan' => 'nullable|integer|min:0'
            ], [
                'judul.required' => 'Judul foto wajib diisi.',
                'judul.max' => 'Judul foto maksimal 255 karakter.',
                'gambar.image' => 'File harus berupa gambar.',
                'gambar.mimes' => 'Format gambar harus JPEG, PNG, atau JPG.',
                'gambar.max' => 'Ukuran gambar maksimal 2MB.',
                'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
                'urutan.integer' => 'Urutan harus berupa angka.',
                'urutan.min' => 'Urutan minimal 0.'
            ]);

            $data = [
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'aktif' => $request->boolean('aktif', true),
                'urutan' => $request->urutan ?? 0
            ];

            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($galeri->gambar && Storage::disk('public')->exists($galeri->gambar)) {
                    Storage::disk('public')->delete($galeri->gambar);
                }
                
                $gambar = $request->file('gambar');
                $data['gambar'] = $gambar->store('galeri', 'public');
            }

            $galeri->update($data);

            return redirect()->route('galeri.index')
                ->with('success', 'Foto berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error in GaleriController@update: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui foto.')
                ->withInput();
        }
    }

    public function destroy(Galeri $galeri)
    {
        try {
            // Hapus file gambar dari storage
            if ($galeri->gambar && Storage::disk('public')->exists($galeri->gambar)) {
                Storage::disk('public')->delete($galeri->gambar);
            }

            $galeri->delete();

            return redirect()->route('galeri.index')
                ->with('success', 'Foto berhasil dihapus dari galeri.');
        } catch (\Exception $e) {
            Log::error('Error in GaleriController@destroy: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus foto.');
        }
    }
} 