<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DokumenController extends Controller
{
    public function index()
    {
        $dokumen = Dokumen::with('user')->orderBy('created_at', 'desc')->get();
        return view('dokumen.index', compact('dokumen'));
    }

    public function create()
    {
        return view('dokumen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|in:panduan,template,laporan,lainnya',
            'keterangan' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240'
        ]);

        $file = $request->file('file');
        $path = $file->store('dokumen', 'public');

        $dokumen = Dokumen::create([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan,
            'file_path' => $path,
            'ukuran' => $file->getSize(),
            'user_id' => auth()->id()
        ]);

        return redirect()->route('dokumen.index')
            ->with('success', 'Dokumen berhasil diupload.');
    }

    public function show(Dokumen $dokumen)
    {
        $this->authorize('view', $dokumen);
        return view('dokumen.show', compact('dokumen'));
    }

    public function edit(Dokumen $dokumen)
    {
        return view('dokumen.edit', compact('dokumen'));
    }

    public function update(Request $request, Dokumen $dokumen)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|in:panduan,template,laporan,lainnya',
            'keterangan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240'
        ]);

        $data = [
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan
        ];

        if ($request->hasFile('file')) {
            // Hapus file lama
            if ($dokumen->file_path) {
                Storage::disk('public')->delete($dokumen->file_path);
            }

            // Upload file baru
            $file = $request->file('file');
            $path = $file->store('dokumen', 'public');

            $data['file_path'] = $path;
            $data['ukuran'] = $file->getSize();
        }

        $dokumen->update($data);

        return redirect()->route('dokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Dokumen $dokumen)
    {
        if ($dokumen->file_path) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        return redirect()->route('dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    public function download(Dokumen $dokumen)
    {
        if (!$dokumen->file_path || !Storage::disk('public')->exists($dokumen->file_path)) {
            abort(404);
        }

        $extension = pathinfo($dokumen->file_path, PATHINFO_EXTENSION);
        $filename = Str::slug($dokumen->nama) . '.' . $extension;

        return Storage::disk('public')->download($dokumen->file_path, $filename);
    }

    public function kategori($jenis)
    {
        $query = Dokumen::with('user')->orderBy('created_at', 'desc');
        
        if ($jenis !== 'all') {
            $query->where('jenis', $jenis);
        }

        $dokumen = $query->paginate(12);
        $totalDokumen = $query->count();

        return view('dokumen.kategori', compact('dokumen', 'jenis', 'totalDokumen'));
    }
} 