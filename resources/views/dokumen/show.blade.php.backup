<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Dokumen') }}
            </h2>
            <div>
                @can('update', $dokumen)
                    <a href="{{ route('dokumen.edit', $dokumen) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i> Edit
                    </a>
                @endcan
                <a href="{{ route('dokumen.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Informasi Dokumen</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="200">Nama Dokumen</th>
                                            <td>{{ $dokumen->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jenis</th>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $dokumen->getJenisOptions()[$dokumen->jenis] }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($dokumen->is_public)
                                                    <span class="badge bg-success">Publik</span>
                                                @else
                                                    <span class="badge bg-warning">Private</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Deskripsi</th>
                                            <td>{{ $dokumen->deskripsi ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Diupload Oleh</th>
                                            <td>{{ $dokumen->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Upload</th>
                                            <td>{{ $dokumen->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Terakhir Diupdate</th>
                                            <td>{{ $dokumen->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Preview</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $extension = pathinfo($dokumen->file_path, PATHINFO_EXTENSION);
                                    @endphp

                                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'svg']))
                                        <img src="{{ Storage::url($dokumen->file_path) }}" alt="{{ $dokumen->nama }}" class="img-fluid rounded">
                                    @else
                                        <div class="text-center py-5">
                                            @if($extension == 'pdf')
                                                <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                                            @else
                                                <i class="fas fa-file-word fa-5x text-primary mb-3"></i>
                                            @endif
                                            <p>{{ strtoupper($extension) }} Document</p>
                                        </div>
                                    @endif

                                    <div class="d-grid gap-2 mt-3">
                                        <a href="{{ route('dokumen.download', $dokumen) }}" class="btn btn-primary">
                                            <i class="fas fa-download me-2"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 