<?php

namespace App\Providers;

use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\Logbook;
use App\Models\Pengaduan;
use App\Policies\BeritaPolicy;
use App\Policies\DokumenPolicy;
use App\Policies\GaleriPolicy;
use App\Policies\LogbookPolicy;
use App\Policies\PengaduanPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Berita::class => BeritaPolicy::class,
        Dokumen::class => DokumenPolicy::class,
        Galeri::class => GaleriPolicy::class,
        Logbook::class => LogbookPolicy::class,
        Pengaduan::class => PengaduanPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
} 