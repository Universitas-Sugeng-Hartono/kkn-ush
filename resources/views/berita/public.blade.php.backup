<x-public-layout>
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('berita.public.index') }}">Berita</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $berita->judul }}</li>
                </ol>
            </nav>
            <h1>{{ $berita->judul }}</h1>
            <div class="d-flex align-items-center mt-3">
                <div class="me-4">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ $berita->published_at ? $berita->published_at->format('d M Y') : '-' }}
                </div>
                <div>
                    <i class="fas fa-user me-1"></i>
                    {{ $berita->user->name ?? 'Admin' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    @if($berita->gambar)
                        <img src="{{ $berita->gambar_url }}" 
                            class="card-img-top" 
                            alt="{{ $berita->judul }}" 
                            style="max-height: 400px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <div class="article-content">
                            {!! $berita->konten !!}
                        </div>

                        <hr class="my-4">

                        <!-- Share Buttons -->
                        <div class="d-flex align-items-center">
                            <span class="me-3">Bagikan:</span>
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

                <!-- Back Button -->
                <div class="text-center mt-4">
                    <a href="{{ route('berita.public.index') }}" 
                        class="btn btn-lg" 
                        style="border: 2px solid #0B1F3A; color: #0B1F3A;">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Berita
                    </a>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Recent Posts -->
                <div class="card mb-4">
                    <div class="card-header" style="background-color: #f2b70d;">
                        <h5 class="card-title mb-0" style="color: #0B1F3A;">Berita Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($recentPosts as $post)
                                <a href="{{ route('berita.public.show', $post) }}" 
                                    class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ Str::limit($post->judul, 50) }}</h6>
                                        <small>{{ $post->published_at ? $post->published_at->format('d/m/Y') : '-' }}</small>
                                    </div>
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
        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .article-content p {
            margin-bottom: 1.5rem;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 1.5rem 0;
        }

        .list-group-item-action:hover {
            background-color: #f8f9fa;
            border-left: 4px solid #f2b70d;
        }
    </style>
    @endpush
</x-public-layout> 