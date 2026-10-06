@extends('layouts.app')

@section('title', 'Detail Disposisi')

@section('content')
<div class="page-header">
    <div>
        <h1>Detail Disposisi</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('disposisi.index') }}" class="text-decoration-none">Disposisi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('disposisi.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Main content -->
    <div class="col-lg-8">
        <!-- Surat Disposisi Card -->
        <div class="card mb-4 border-0 shadow-sm overflow-hidden">
            <div class="bg-primary text-white p-4 pb-5" style="background: var(--gradient-primary);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-envelope-paper me-2"></i> Surat Disposisi</h4>
                    <span class="badge bg-white text-dark rounded-pill px-3 py-2 fs-6 shadow-sm">
                        <i class="bi bi-circle-fill text-{{ $disposisi->status_badge }} me-1 small"></i>
                        {{ ucfirst($disposisi->status) }}
                    </span>
                </div>
                <div class="small opacity-75">
                    <i class="bi bi-clock me-1"></i> Dikirim pada {{ $disposisi->created_at->format('d M Y, H:i') }} WIB
                </div>
            </div>
            
            <div class="card-body bg-white p-4" style="margin-top: -20px; border-radius: 20px 20px 0 0;">
                <!-- Pengirim & Penerima -->
                <div class="row g-0 mb-4 p-3 bg-light rounded-4 border">
                    <div class="col-md-5">
                        <div class="text-muted small text-uppercase fw-bold mb-2 tracking-wide" style="letter-spacing: 1px;">Dari:</div>
                        <div class="d-flex align-items-center gap-3">
                            @if($disposisi->dariPegawai && $disposisi->dariPegawai->foto)
                                <img src="{{ asset('storage/' . $disposisi->dariPegawai->foto) }}" class="rounded-circle shadow-sm border" width="48" height="48" style="object-fit: cover;">
                            @else
                                <div class="user-avatar shadow-sm" style="width: 48px; height: 48px; font-size: 18px;">
                                    {{ $disposisi->dariPegawai ? $disposisi->dariPegawai->inisials : '?' }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold text-dark">{{ $disposisi->dariPegawai->nama ?? 'Unknown' }}</div>
                                <div class="text-secondary small">{{ $disposisi->dariPegawai->jabatan ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 d-flex justify-content-center align-items-center py-3 py-md-0">
                        <div class="bg-white rounded-circle shadow-sm border d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-arrow-right fs-4 text-primary d-none d-md-block"></i>
                            <i class="bi bi-arrow-down fs-4 text-primary d-block d-md-none"></i>
                        </div>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="text-muted small text-uppercase fw-bold mb-2 tracking-wide" style="letter-spacing: 1px;">Kepada:</div>
                        <div class="d-flex align-items-center gap-3">
                            @if($disposisi->kepadaPegawai && $disposisi->kepadaPegawai->foto)
                                <img src="{{ asset('storage/' . $disposisi->kepadaPegawai->foto) }}" class="rounded-circle shadow-sm border" width="48" height="48" style="object-fit: cover;">
                            @else
                                <div class="user-avatar shadow-sm" style="width: 48px; height: 48px; font-size: 18px;">
                                    {{ $disposisi->kepadaPegawai ? $disposisi->kepadaPegawai->inisials : '?' }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold text-dark">{{ $disposisi->kepadaPegawai->nama ?? 'Unknown' }}</div>
                                <div class="text-secondary small">{{ $disposisi->kepadaPegawai->jabatan ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Isi Disposisi -->
                <div class="mb-2">
                    <h6 class="text-secondary fw-bold text-uppercase small mb-3" style="letter-spacing: 1px;">Isi Disposisi / Arahan:</h6>
                    <div class="p-4 bg-primary bg-opacity-10 rounded-4 border border-primary border-opacity-25 fs-5 fst-italic text-dark">
                        "{{ $disposisi->catatan }}"
                    </div>
                </div>
            </div>
            
            <!-- Update Status Action (Only for Receiver) -->
            @if(auth()->check() && auth()->user()->pegawai && auth()->user()->pegawai->id === $disposisi->kepada_pegawai_id)
                @if(in_array($disposisi->status, ['pending', 'dibaca', 'diproses']))
                <div class="card-footer bg-white border-top p-4">
                    <h6 class="text-secondary fw-bold text-uppercase small mb-3" style="letter-spacing: 1px;">Tindak Lanjut Anda:</h6>
                    <form action="{{ route('disposisi.update-status', $disposisi) }}" method="POST" class="d-flex flex-wrap gap-2">
                        @csrf
                        @method('PATCH')
                        
                        @if($disposisi->status !== 'diproses')
                        <button type="submit" name="status" value="diproses" class="btn btn-outline-primary px-4 py-2 fw-semibold rounded-pill">
                            <i class="bi bi-play-circle me-1"></i> Mulai Proses
                        </button>
                        @endif
                        
                        <button type="submit" name="status" value="selesai" class="btn btn-success px-4 py-2 fw-semibold rounded-pill shadow-sm" onclick="return confirm('Tandai disposisi ini telah diselesaikan?');">
                            <i class="bi bi-check-circle me-1"></i> Tandai Selesai
                        </button>
                        
                        <button type="submit" name="status" value="ditolak" class="btn btn-outline-danger px-4 py-2 fw-semibold rounded-pill ms-auto" onclick="return confirm('Tolak/kembalikan disposisi ini?');">
                            <i class="bi bi-x-circle me-1"></i> Tolak
                        </button>
                    </form>
                </div>
                @endif
            @endif
        </div>
        
        <!-- Timeline Status -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-bottom p-3">
                <h6 class="mb-0 fw-bold">Riwayat Status</h6>
            </div>
            <div class="card-body p-4">
                <div class="position-relative">
                    <!-- Line -->
                    <div class="position-absolute h-100 border-start border-2 border-primary" style="left: 11px; top: 10px; z-index: 1;"></div>
                    
                    <!-- Dikirim -->
                    <div class="d-flex mb-4 position-relative z-2">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 24px; height: 24px; flex-shrink: 0;">
                            <i class="bi bi-check" style="font-size: 14px;"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Dikirim oleh {{ $disposisi->dariPegawai->nama ?? 'Pengirim' }}</div>
                            <div class="text-muted small">{{ $disposisi->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                    
                    <!-- Dibaca -->
                    <div class="d-flex mb-4 position-relative z-2">
                        <div class="{{ $disposisi->dibaca_pada ? 'bg-primary' : 'bg-light border' }} text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 24px; height: 24px; flex-shrink: 0;">
                            @if($disposisi->dibaca_pada) <i class="bi bi-check" style="font-size: 14px;"></i> @endif
                        </div>
                        <div>
                            <div class="{{ $disposisi->dibaca_pada ? 'fw-bold' : 'text-muted' }}">Dibaca oleh Penerima</div>
                            @if($disposisi->dibaca_pada)
                                <div class="text-muted small">{{ $disposisi->dibaca_pada->format('d M Y, H:i') }}</div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Selesai -->
                    <div class="d-flex position-relative z-2">
                        <div class="{{ $disposisi->status === 'selesai' ? 'bg-success' : ($disposisi->status === 'ditolak' ? 'bg-danger' : 'bg-light border') }} text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 24px; height: 24px; flex-shrink: 0;">
                            @if(in_array($disposisi->status, ['selesai', 'ditolak'])) <i class="bi bi-check" style="font-size: 14px;"></i> @endif
                        </div>
                        <div>
                            <div class="{{ in_array($disposisi->status, ['selesai', 'ditolak']) ? 'fw-bold' : 'text-muted' }}">
                                @if($disposisi->status === 'selesai')
                                    Selesai Dikerjakan
                                @elseif($disposisi->status === 'ditolak')
                                    Ditolak/Dibatalkan
                                @else
                                    Selesai
                                @endif
                            </div>
                            @if(in_array($disposisi->status, ['selesai', 'ditolak']))
                                <div class="text-muted small">{{ $disposisi->updated_at->format('d M Y, H:i') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar / Kegiatan Info -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-dark text-white p-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-journal-text me-2"></i> Informasi Kegiatan Terkait</h6>
            </div>
            <div class="card-body p-0">
                <div class="p-4 border-bottom">
                    <h5 class="fw-bold mb-3 text-primary">{{ $disposisi->kegiatan->judul }}</h5>
                    
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-calendar-event text-secondary fs-5 mt-1"></i>
                            <div>
                                <div class="small text-muted fw-semibold">Tanggal</div>
                                <div class="fw-medium">{{ $disposisi->kegiatan->tanggal->isoFormat('dddd, D MMMM Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-clock text-secondary fs-5 mt-1"></i>
                            <div>
                                <div class="small text-muted fw-semibold">Waktu</div>
                                <div class="fw-medium">{{ \Carbon\Carbon::parse($disposisi->kegiatan->jam_mulai)->format('H:i') }} - {{ $disposisi->kegiatan->jam_selesai ? \Carbon\Carbon::parse($disposisi->kegiatan->jam_selesai)->format('H:i') : 'Selesai' }}</div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-geo-alt text-secondary fs-5 mt-1"></i>
                            <div>
                                <div class="small text-muted fw-semibold">Tempat</div>
                                <div class="fw-medium">{{ $disposisi->kegiatan->tempat }}</div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-tags text-secondary fs-5 mt-1"></i>
                            <div>
                                <div class="small text-muted fw-semibold mb-1">Kategori & Prioritas</div>
                                <div>
                                    <span class="badge" style="background-color: {{ $disposisi->kegiatan->kategori->warna ?? '#6c757d' }}">
                                        {{ $disposisi->kegiatan->kategori->nama ?? 'Umum' }}
                                    </span>
                                    <span class="badge bg-{{ $disposisi->kegiatan->prioritas_badge }} ms-1">
                                        {{ strtoupper($disposisi->kegiatan->prioritas) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 bg-light text-center">
                    <a href="{{ route('kegiatan.show', $disposisi->kegiatan) }}" class="btn btn-outline-primary w-100 fw-semibold rounded-pill">
                        Lihat Detail Kegiatan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
