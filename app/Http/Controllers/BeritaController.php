<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BeritaController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', Berita::class);

        $berita = Berita::with('user')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->status = $this->getBeritaStatus($item);
                return $item;
            });

        return view('berita.index', compact('berita'));
    }

    private function getBeritaStatus($berita)
    {
        if (!$berita->is_published) {
            return [
                'text' => 'Draft',
                'class' => 'bg-warning',
                'icon' => 'fa-clock'
            ];
        }

        if (!$berita->published_at) {
            return [
                'text' => 'Menunggu Publikasi',
                'class' => 'bg-info',
                'icon' => 'fa-hourglass-half'
            ];
        }

        $publishDate = Carbon::parse($berita->published_at);
        if ($publishDate->isFuture()) {
            return [
                'text' => 'Terjadwal',
                'class' => 'bg-primary',
                'icon' => 'fa-calendar-alt'
            ];
        }

        return [
            'text' => 'Dipublikasi',
            'class' => 'bg-success',
            'icon' => 'fa-check-circle'
        ];
    }

    public function create()
    {
        // $this->authorize('create', Berita::class);
        return view('berita.create');
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Berita::class);

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_published' => 'boolean'
        ]);

        $data = [
            'judul' => $request->judul,
            'konten' => $request->konten,
            'user_id' => auth()->id(),
            'is_published' => $request->boolean('is_published', false),
            'published_at' => $request->boolean('is_published') ? now() : null
        ];

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $data['gambar'] = $gambar->store('berita', 'public');
        }

        Berita::create($data);

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function show(Berita $berita)
    {
        return view('berita.show', compact('berita'));
    }

    public function edit(Berita $berita)
    {
        // $this->authorize('update', $berita);
        return view('berita.edit', compact('berita'));
    }

    public function update(Request $request, Berita $berita)
    {
        // $this->authorize('update', $berita);

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_published' => 'boolean'
        ]);

        $data = [
            'judul' => $request->judul,
            'konten' => $request->konten,
            'is_published' => $request->boolean('is_published', false)
        ];

        // Set published_at jika status berubah menjadi published
        if ($request->boolean('is_published') && !$berita->is_published) {
            $data['published_at'] = now();
        }
        // Reset published_at jika status berubah menjadi draft
        elseif (!$request->boolean('is_published') && $berita->is_published) {
            $data['published_at'] = null;
        }

        if ($request->hasFile('gambar')) {
            if ($berita->gambar) {
                Storage::disk('public')->delete($berita->gambar);
            }
            
            $gambar = $request->file('gambar');
            $data['gambar'] = $gambar->store('berita', 'public');
        }

        $berita->update($data);

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita)
    {
        // $this->authorize('delete', $berita);

        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    public function publicIndex()
    {
        $berita = Berita::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('berita.public_index', compact('berita'));
    }

    public function publicShow(Berita $berita)
    {
        if (!$berita->is_published) {
            abort(404);
        }

        $recentPosts = Berita::where('is_published', true)
            ->where('id', '!=', $berita->id)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        return view('berita.public', compact('berita', 'recentPosts'));
    }
} 