@extends('layouts.app')

@section('title', 'Profil Pegawai: ' . $pegawai->nama)

@section('content')
<div class="page-header">
    <div>
        <h1>Profil Pegawai</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pegawai.index') }}" class="text-decoration-none">Pegawai</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profil</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->id === $pegawai->user_id))
        <a href="{{ route('pegawai.edit', $pegawai) }}" class="btn btn-warning text-dark">
            <i class="bi bi-pencil me-1"></i> Edit Profil
        </a>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Info Profil -->
    <div class="col-lg-4">
        <!-- Card Profil -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center p-5">
                <div class="mb-4">
                    @if($pegawai->foto)
                        <img src="{{ asset('storage/' . $pegawai->foto) }}" alt="{{ $pegawai->nama }}" class="rounded-circle shadow border border-3 border-white" width="140" height="140" style="object-fit: cover;">
                    @else
                        <div class="user-avatar shadow border border-3 border-white d-flex justify-content-center align-items-center mx-auto" style="width: 140px; height: 140px; font-size: 48px; background: var(--gradient-primary); color: white;">
                            {{ $pegawai->inisials }}
                        </div>
                    @endif
                </div>
                
                <h4 class="fw-bold text-primary mb-1">{{ $pegawai->nama }}</h4>
                <div class="text-secondary mb-3">{{ $pegawai->nip }}</div>
                
                <span class="badge bg-{{ $pegawai->status == 'aktif' ? 'success' : 'danger' }} px-3 py-2 rounded-pill fs-6 fw-medium shadow-sm mb-4">
                    {{ ucfirst($pegawai->status) }}
                </span>
                
                <div class="d-flex justify-content-center gap-3">
                    <div class="text-center">
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalKegiatan }}</h4>
                        <span class="small text-muted">Total Kegiatan</span>
                    </div>
                    <div class="border-end"></div>
                    <div class="text-center">
                        <h4 class="fw-bold mb-0 text-primary">{{ $kegiatanBulanIni }}</h4>
                        <span class="small text-muted">Bulan Ini</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card Detail Info -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-info-circle text-primary me-2"></i> Informasi Pekerjaan</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-4 py-3">
                        <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Jabatan</div>
                        <div class="fw-medium text-dark mt-1">{{ $pegawai->jabatan }}</div>
                    </li>
                    <li class="list-group-item px-4 py-3">
                        <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Pangkat / Golongan</div>
                        <div class="fw-medium text-dark mt-1">{{ $pegawai->pangkat_golongan ?? '-' }}</div>
                    </li>
                    <li class="list-group-item px-4 py-3">
                        <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Unit Kerja</div>
                        <div class="fw-medium text-dark mt-1">{{ $pegawai->unit_kerja ?? '-' }}</div>
                    </li>
                    <li class="list-group-item px-4 py-3">
                        <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">No. Handphone</div>
                        <div class="fw-medium text-dark mt-1">{{ $pegawai->no_hp ?? '-' }}</div>
                    </li>
                    
                    @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->id === $pegawai->user_id))
                    <li class="list-group-item px-4 py-3 bg-light">
                        <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Status Akun</div>
                        <div class="mt-1 d-flex align-items-center">
                            @if($pegawai->user)
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="fw-medium">Terhubung (Role: {{ ucfirst($pegawai->user->role) }})</span>
                            @else
                                <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                <span class="fw-medium">Belum ada akun</span>
                            @endif
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Kolom Kanan: Riwayat Kegiatan -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-history text-primary me-2"></i> Riwayat Kegiatan Terakhir</h5>
                <a href="{{ route('rekapitulasi.detail', $pegawai) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    Lihat Rekap Lengkap
                </a>
            </div>
            <div class="card-body p-0">
                @if($pegawai->kegiatan->isEmpty())
                    <div class="empty-state py-5 text-center">
                        <i class="bi bi-calendar-x mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                        <h6 class="text-secondary">Belum ada riwayat kegiatan</h6>
                        <p class="small text-muted">Pegawai ini belum terdaftar di kegiatan apapun.</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($pegawai->kegiatan as $kegiatan)
                        <a href="{{ route('kegiatan.show', $kegiatan) }}" class="list-group-item list-group-item-action p-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0 fw-bold text-primary">{{ $kegiatan->judul }}</h6>
                                
                                @php
                                    $statusHadir = $kegiatan->pivot->status_kehadiran;
                                    $badgeClass = match($statusHadir) {
                                        'hadir' => 'success',
                                        'izin' => 'warning',
                                        'alpa' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($statusHadir) }}</span>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-3 text-secondary small mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $kegiatan->tanggal->format('d M Y') }}
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($kegiatan->jam_mulai)->format('H:i') }} - {{ $kegiatan->jam_selesai ? \Carbon\Carbon::parse($kegiatan->jam_selesai)->format('H:i') : 'Selesai' }}
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $kegiatan->tempat }}
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                @if($kegiatan->kategori)
                                    <span class="badge" style="background-color: {{ $kegiatan->kategori->warna ?? '#6c757d' }}; opacity: 0.9;">
                                        <i class="bi {{ $kegiatan->kategori->icon ?? 'bi-tag' }} me-1"></i> {{ $kegiatan->kategori->nama }}
                                    </span>
                                @else
                                    <span></span>
                                @endif
                                
                                <span class="badge border border-{{ $kegiatan->status_badge }} text-{{ $kegiatan->status_badge }} bg-white">
                                    Status Kegiatan: {{ ucfirst($kegiatan->status) }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
