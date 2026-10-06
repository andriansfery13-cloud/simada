@extends('layouts.app')

@section('title', 'Detail Rekapitulasi: ' . $pegawai->nama)

@section('content')
<div class="page-header">
    <div>
        <h1>Detail Rekapitulasi Kehadiran</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rekapitulasi.index') }}" class="text-decoration-none">Rekapitulasi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('rekapitulasi.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-primary text-white" style="background: var(--gradient-primary);">
            <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-4">
                <div class="d-flex align-items-center gap-4">
                    @if($pegawai->foto)
                        <img src="{{ asset('storage/' . $pegawai->foto) }}" alt="Avatar" class="rounded-circle shadow border border-3 border-white" width="80" height="80" style="object-fit: cover;">
                    @else
                        <div class="user-avatar shadow border border-3 border-white bg-white text-primary" style="width: 80px; height: 80px; font-size: 28px;">
                            {{ $pegawai->inisials }}
                        </div>
                    @endif
                    <div>
                        <h4 class="fw-bold mb-1">{{ $pegawai->nama }}</h4>
                        <div class="opacity-75 mb-2">{{ $pegawai->nip }} | {{ $pegawai->jabatan }}</div>
                        <div class="badge bg-white text-primary rounded-pill px-3 shadow-sm">
                            <i class="bi bi-calendar-event me-1"></i> Periode: {{ \Carbon\Carbon::parse($bulanSekarang . '-01')->translatedFormat('F Y') }}
                        </div>
                    </div>
                </div>
                
                <div class="d-flex gap-4 flex-wrap justify-content-center mt-3 mt-md-0">
                    <div class="text-center px-md-3 border-md-end border-light border-opacity-25">
                        <div class="fs-2 fw-bold lh-1 mb-1">{{ $kegiatan->count() }}</div>
                        <div class="small opacity-75 text-uppercase" style="letter-spacing: 1px;">Total</div>
                    </div>
                    
                    @php 
                        $hadir = $kegiatan->where('pivot.status_kehadiran', 'hadir')->count();
                        $izin = $kegiatan->where('pivot.status_kehadiran', 'izin')->count();
                        $alpa = $kegiatan->where('pivot.status_kehadiran', 'alpa')->count();
                    @endphp
                    
                    <div class="text-center px-md-3 border-md-end border-light border-opacity-25">
                        <div class="fs-2 fw-bold lh-1 mb-1 text-success text-opacity-75">{{ $hadir }}</div>
                        <div class="small opacity-75 text-uppercase" style="letter-spacing: 1px;">Hadir</div>
                    </div>
                    
                    <div class="text-center px-md-3 border-md-end border-light border-opacity-25">
                        <div class="fs-2 fw-bold lh-1 mb-1 text-warning text-opacity-75">{{ $izin }}</div>
                        <div class="small opacity-75 text-uppercase" style="letter-spacing: 1px;">Izin</div>
                    </div>
                    
                    <div class="text-center px-md-3">
                        <div class="fs-2 fw-bold lh-1 mb-1 text-danger text-opacity-75">{{ $alpa }}</div>
                        <div class="small opacity-75 text-uppercase" style="letter-spacing: 1px;">Alpa</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-list-check text-primary me-2"></i> Daftar Riwayat Kehadiran</h5>
            
            <form method="GET" action="{{ route('rekapitulasi.detail', $pegawai) }}" class="d-flex gap-2">
                <input type="month" name="bulan" class="form-control form-control-sm" value="{{ $bulanSekarang }}" onchange="this.form.submit()">
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        {{-- DESKTOP VIEW --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th width="35%">Kegiatan</th>
                        <th>Peran</th>
                        <th>Status Kehadiran</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatan as $keg)
                    <tr>
                        <td>
                            <div class="fw-medium text-dark">{{ $keg->tanggal->format('d M Y') }}</div>
                            <div class="text-muted small">{{ $keg->tanggal->translatedFormat('l') }}</div>
                        </td>
                        <td>
                            <div class="text-muted small">
                                <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($keg->jam_mulai)->format('H:i') }} - {{ $keg->jam_selesai ? \Carbon\Carbon::parse($keg->jam_selesai)->format('H:i') : 'Selesai' }}
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold mb-1">{{ $keg->judul }}</div>
                            <div class="text-muted small"><i class="bi bi-geo-alt text-danger me-1"></i> {{ $keg->tempat }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">Peserta</span>
                        </td>
                        <td>
                            @php
                                $statusHadir = $keg->pivot->status_kehadiran;
                                $badgeClass = match($statusHadir) {
                                    'hadir' => 'success',
                                    'izin' => 'warning',
                                    'alpa' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }} bg-opacity-25 text-{{ $badgeClass == 'warning' ? 'dark' : $badgeClass }} border border-{{ $badgeClass }} px-3 py-2 rounded-pill">
                                @if($statusHadir == 'hadir') <i class="bi bi-check-circle me-1"></i>
                                @elseif($statusHadir == 'izin') <i class="bi bi-exclamation-circle me-1"></i>
                                @elseif($statusHadir == 'alpa') <i class="bi bi-x-circle me-1"></i>
                                @endif
                                {{ ucfirst($statusHadir) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('kegiatan.show', $keg) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Lihat Kegiatan">
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state py-5">
                                <i class="bi bi-cup-hot mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                <h6>Tidak ada data kegiatan di bulan ini</h6>
                                <p class="text-muted small">Pegawai tidak terdaftar dalam kegiatan apapun pada periode yang dipilih.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE VIEW --}}
        <div class="d-md-none">
            @forelse($kegiatan as $keg)
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-bold text-dark">{{ $keg->judul }}</div>
                        <div class="small text-muted mt-1">
                            <i class="bi bi-calendar-event me-1"></i> {{ $keg->tanggal->format('d M Y') }}
                        </div>
                    </div>
                    @php
                        $statusHadir = $keg->pivot->status_kehadiran;
                        $badgeClass = match($statusHadir) {
                            'hadir' => 'success',
                            'izin' => 'warning',
                            'alpa' => 'danger',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $badgeClass }} bg-opacity-25 text-{{ $badgeClass == 'warning' ? 'dark' : $badgeClass }} border border-{{ $badgeClass }} px-2 py-1 rounded-pill" style="font-size: 0.7rem;">
                        {{ ucfirst($statusHadir) }}
                    </span>
                </div>
                
                <div class="small text-muted mb-3">
                    <div class="mb-1"><i class="bi bi-clock text-secondary me-2"></i> {{ \Carbon\Carbon::parse($keg->jam_mulai)->format('H:i') }} - {{ $keg->jam_selesai ? \Carbon\Carbon::parse($keg->jam_selesai)->format('H:i') : 'Selesai' }}</div>
                    <div><i class="bi bi-geo-alt text-danger me-2"></i> {{ $keg->tempat }}</div>
                </div>
                
                <a href="{{ route('kegiatan.show', $keg) }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                    Detail Kegiatan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            @empty
            <div class="empty-state py-5">
                <i class="bi bi-cup-hot mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                <h6>Tidak ada data kegiatan di bulan ini</h6>
                <p class="text-muted small">Pegawai tidak terdaftar dalam kegiatan apapun pada periode yang dipilih.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
