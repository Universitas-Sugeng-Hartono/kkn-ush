<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Berita') }}
            </h2>
            <div>
                @can('update', $berita)
                    <a href="{{ route('berita.edit', $berita) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i> Edit
                    </a>
                @endcan
                <a href="{{ route('berita.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Page Header -->
    <div class="page-header" style="background: linear-gradient(135deg, #0B1F3A 0%, #1a365d 100%); color: white; padding: 3rem 0; margin-bottom: 2rem;">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-4" style="background: transparent; color: rgba(255,255,255,0.8);">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: rgba(255,255,255,0.8);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('berita.index') }}" style="color: rgba(255,255,255,0.8);">Berita</a></li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: white;">{{ $berita->judul }}</li>
                </ol>
            </nav>
            <h1 class="display-4 fw-bold mb-3">{{ $berita->judul }}</h1>
            <div class="d-flex align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user me-2"></i>
                    <span>{{ $berita->user->name ?? 'Admin' }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <span>{{ $berita->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-tag me-2"></i>
                    @if($berita->is_published)
                        <span class="badge" style="background-color: #28a745; color: white;">Dipublikasi</span>
                    @else
                        <span class="badge" style="background-color: #ffc107; color: #212529;">Draft</span>
                    @endif
                </div>
                @if($berita->published_at)
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock me-2"></i>
                        <span>Dipublikasikan: {{ $berita->published_at->format('d M Y H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    @if($berita->gambar)
                        <img src="{{ $berita->gambar_url }}" 
                            class="card-img-top" 
                            alt="{{ $berita->judul }}" 
                            style="max-height: 500px; object-fit: cover;">
                    @endif
                    <div class="card-body p-5">
                        @if(session('success'))
                            <div class="alert alert-success mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="article-content">
                            {!! $berita->konten !!}
                        </div>

                        <hr class="my-5">

                        <!-- Share Buttons -->
                        <div class="d-flex align-items-center">
                            <span class="me-3 fw-bold">Bagikan:</span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                                target="_blank" 
                                class="btn btn-sm me-2" 
                                style="background-color: #1877f2; color: white;">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($berita->judul) }}" 
                                target="_blank" 
                                class="btn btn-sm me-2" 
                                style="background-color: #1da1f2; color: white;">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($berita->judul . ' ' . request()->url()) }}" 
                                target="_blank" 
                                class="btn btn-sm" 
                                style="background-color: #25d366; color: white;">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <div class="btn-group" role="group">
                        @can('update', $berita)
                            <a href="{{ route('berita.edit', $berita) }}" 
                                class="btn btn-lg btn-warning me-2">
                                <i class="fas fa-edit me-2"></i>Edit Berita
                            </a>
                        @endcan
                        <a href="{{ route('berita.index') }}" 
                            class="btn btn-lg" 
                            style="border: 2px solid #0B1F3A; color: #0B1F3A;">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header" style="background-color: #f2b70d;">
                        <h5 class="card-title mb-0" style="color: #0B1F3A;">
                            <i class="fas fa-info-circle me-2"></i>Status Berita
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-eye me-2"></i>
                            <span>Status: 
                                @if($berita->is_published)
                                    <span class="badge bg-success">Dipublikasi</span>
                                @else
                                    <span class="badge bg-warning">Draft</span>
                                @endif
                            </span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-user me-2"></i>
                            <span>Penulis: {{ $berita->user->name ?? 'Admin' }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-calendar me-2"></i>
                            <span>Dibuat: {{ $berita->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($berita->published_at)
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock me-2"></i>
                                <span>Dipublikasi: {{ $berita->published_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Posts -->
                <div class="card shadow-sm border-0">
                    <div class="card-header" style="background-color: #f2b70d;">
                        <h5 class="card-title mb-0" style="color: #0B1F3A;">
                            <i class="fas fa-newspaper me-2"></i>Berita Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @php
                                $recentPosts = \App\Models\Berita::where('id', '!=', $berita->id)
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();
                            @endphp
                            @foreach($recentPosts as $post)
                                <a href="{{ route('berita.show', $post) }}" 
                                    class="list-group-item list-group-item-action border-0">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ Str::limit($post->judul, 50) }}</h6>
                                        <small class="text-muted">{{ $post->created_at->format('d/m/Y') }}</small>
                                    </div>
                                    <small class="text-muted">
                                        @if($post->is_published)
                                            <span class="badge bg-success">Dipublikasi</span>
                                        @else
                                            <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .page-header {
            background: linear-gradient(135deg, #0B1F3A 0%, #1a365d 100%);
        }

        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
        }

        .article-content p {
            margin-bottom: 1.5rem;
        }

        .article-content h1, .article-content h2, .article-content h3, 
        .article-content h4, .article-content h5, .article-content h6 {
            color: #0B1F3A;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 1.5rem 0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .article-content ul, .article-content ol {
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }

        .article-content li {
            margin-bottom: 0.5rem;
        }

        .list-group-item-action:hover {
            background-color: #f8f9fa;
            border-left: 4px solid #f2b70d;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .card-header {
            border-bottom: none;
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255,255,255,0.6);
        }
    </style>
    @endpush
</x-app-layout> 