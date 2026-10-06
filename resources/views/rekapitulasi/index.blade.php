@extends('layouts.app')

@section('title', 'Rekapitulasi Kegiatan Pegawai')

@section('content')
<div class="page-header">
    <div>
        <h1>Rekapitulasi Kegiatan Pegawai</h1>
        <p class="text-secondary mb-0">Pemantauan aktivitas dan partisipasi pegawai dalam agenda kegiatan.</p>
    </div>
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4 bg-light rounded-top">
        <form method="GET" action="{{ route('rekapitulasi.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Periode Bulan</label>
                <input type="month" name="bulan" class="form-control" value="{{ $bulanSekarang }}">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Cari Pegawai / Jabatan</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Ketik nama atau jabatan..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary"><i class="bi bi-filter me-1"></i> Tampilkan</button>
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        {{-- DESKTOP VIEW (Table) --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-white">
                    <tr>
                        <th width="35%">Informasi Pegawai</th>
                        <th class="text-center">Total Kegiatan</th>
                        <th class="text-center text-success">Hadir</th>
                        <th class="text-center text-warning">Izin</th>
                        <th class="text-center text-danger">Alpa</th>
                        <th class="text-center text-secondary">Belum/Lainnya</th>
                        <th class="text-center">Persentase</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapitulasi as $peg)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($peg->foto)
                                    <img src="{{ asset('storage/' . $peg->foto) }}" alt="Avatar" class="rounded-circle shadow-sm" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <div class="user-avatar shadow-sm" style="width: 40px; height: 40px; font-size: 14px;">
                                        {{ $peg->inisials }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold text-primary">{{ $peg->nama }}</div>
                                    <div class="text-muted small">{{ $peg->jabatan }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center fw-bold fs-5">{{ $peg->total_kegiatan }}</td>
                        <td class="text-center">
                            @if($peg->hadir > 0)
                                <span class="badge bg-success bg-opacity-25 text-success border border-success fw-bold px-2 py-1">{{ $peg->hadir }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($peg->izin > 0)
                                <span class="badge bg-warning bg-opacity-25 text-warning border border-warning fw-bold px-2 py-1">{{ $peg->izin }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($peg->alpa > 0)
                                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger fw-bold px-2 py-1">{{ $peg->alpa }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($peg->belum_absen > 0)
                                <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary fw-bold px-2 py-1">{{ $peg->belum_absen }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                // Hitung persentase kehadiran (hadir / total)
                                $persentase = $peg->total_kegiatan > 0 ? round(($peg->hadir / $peg->total_kegiatan) * 100) : 0;
                                $colorClass = $persentase >= 80 ? 'success' : ($persentase >= 50 ? 'warning' : 'danger');
                            @endphp
                            
                            <div class="d-flex flex-column align-items-center">
                                <span class="fw-bold text-{{ $colorClass }} mb-1">{{ $persentase }}%</span>
                                <div class="progress w-100" style="height: 4px;">
                                    <div class="progress-bar bg-{{ $colorClass }}" role="progressbar" style="width: {{ $persentase }}%" aria-valuenow="{{ $persentase }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('rekapitulasi.detail', ['pegawai' => $peg->id, 'bulan' => $bulanSekarang]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-card-list me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state py-5">
                                <i class="bi bi-clipboard-data mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                <h6>Tidak ada data rekapitulasi pada periode ini.</h6>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE VIEW (Cards) --}}
        <div class="d-md-none">
            @forelse($rekapitulasi as $peg)
            <div class="p-3 border-bottom">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        @if($peg->foto)
                            <img src="{{ asset('storage/' . $peg->foto) }}" alt="Avatar" class="rounded-circle shadow-sm" width="48" height="48" style="object-fit: cover;">
                        @else
                            <div class="user-avatar shadow-sm" style="width: 48px; height: 48px; font-size: 16px;">
                                {{ $peg->inisials }}
                            </div>
                        @endif
                        <div>
                            <div class="fw-bold text-primary">{{ $peg->nama }}</div>
                            <div class="text-muted small">{{ $peg->jabatan }}</div>
                        </div>
                    </div>
                    
                    @php
                        $persentase = $peg->total_kegiatan > 0 ? round(($peg->hadir / $peg->total_kegiatan) * 100) : 0;
                        $colorClass = $persentase >= 80 ? 'success' : ($persentase >= 50 ? 'warning' : 'danger');
                    @endphp
                    <div class="text-end">
                        <div class="fw-bold fs-4 text-{{ $colorClass }} lh-1">{{ $persentase }}%</div>
                        <div class="small text-muted" style="font-size: 0.65rem;">Kehadiran</div>
                    </div>
                </div>
                
                <div class="row g-2 text-center mb-3">
                    <div class="col-3">
                        <div class="bg-light rounded-2 py-1">
                            <div class="fw-bold fs-6">{{ $peg->total_kegiatan }}</div>
                            <div class="text-muted" style="font-size: 0.65rem;">Total</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-2 py-1">
                            <div class="fw-bold fs-6">{{ $peg->hadir }}</div>
                            <div class="text-success" style="font-size: 0.65rem;">Hadir</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-2 py-1">
                            <div class="fw-bold fs-6">{{ $peg->izin }}</div>
                            <div class="text-warning" style="font-size: 0.65rem;">Izin</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-2 py-1">
                            <div class="fw-bold fs-6">{{ $peg->alpa }}</div>
                            <div class="text-danger" style="font-size: 0.65rem;">Alpa</div>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('rekapitulasi.detail', ['pegawai' => $peg->id, 'bulan' => $bulanSekarang]) }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                    <i class="bi bi-card-list me-1"></i> Detail Rekapitulasi
                </a>
            </div>
            @empty
            <div class="empty-state py-5">
                <i class="bi bi-clipboard-data mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                <h6>Tidak ada data rekapitulasi pada periode ini.</h6>
            </div>
            @endforelse
        </div>
    </div>
    
    @if($rekapitulasi->hasPages())
    <div class="card-footer bg-white border-top py-3">
        {{ $rekapitulasi->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
