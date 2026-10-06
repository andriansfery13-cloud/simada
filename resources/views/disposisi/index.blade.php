@extends('layouts.app')

@section('title', 'Disposisi')

@section('content')
<div class="page-header">
    <div>
        <h1>Disposisi</h1>
        <p class="text-secondary mb-0">Manajemen penugasan dan arahan kegiatan.</p>
    </div>
    @if(auth()->check() && auth()->user()->canDisposisi())
    <div>
        <a href="{{ route('disposisi.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Buat Disposisi
        </a>
    </div>
    @endif
</div>

<div class="card mb-4">
    <div class="card-header bg-white pb-0 border-bottom">
        <ul class="nav nav-tabs border-0" id="disposisiTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="{{ route('disposisi.index', ['tab' => 'masuk']) }}" class="nav-link border-0 border-bottom border-3 {{ $tab === 'masuk' ? 'active border-primary text-primary fw-bold' : 'border-transparent text-secondary' }}">
                    <i class="bi bi-inbox me-1"></i> Disposisi Masuk
                    @if($countMasuk > 0)
                        <span class="badge bg-danger rounded-pill ms-1">{{ $countMasuk }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('disposisi.index', ['tab' => 'keluar']) }}" class="nav-link border-0 border-bottom border-3 {{ $tab === 'keluar' ? 'active border-primary text-primary fw-bold' : 'border-transparent text-secondary' }}">
                    <i class="bi bi-send me-1"></i> Disposisi Keluar
                </a>
            </li>
        </ul>
    </div>
    
    <div class="card-body bg-light border-bottom py-3">
        <form method="GET" action="{{ route('disposisi.index') }}" class="row g-2 align-items-center">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="col-auto">
                <label class="col-form-label fw-medium text-secondary">Filter Status:</label>
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm border-0 shadow-sm rounded-3" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="dibaca" {{ request('status') == 'dibaca' ? 'selected' : '' }}>Dibaca</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        {{-- DESKTOP VIEW (Table) --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th width="35%">Kegiatan</th>
                        <th width="20%">{{ $tab === 'masuk' ? 'Pengirim' : 'Penerima' }}</th>
                        <th width="20%">Waktu Disposisi</th>
                        <th width="15%">Status</th>
                        <th width="10%" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disposisi as $dispo)
                    <tr class="{{ $tab === 'masuk' && $dispo->status === 'pending' ? 'table-warning' : '' }}">
                        <td>
                            <div class="fw-bold mb-1">
                                <a href="{{ route('disposisi.show', $dispo) }}" class="text-decoration-none text-primary">{{ $dispo->kegiatan->judul }}</a>
                            </div>
                            <div class="text-muted small text-truncate" style="max-width: 300px;">
                                "{{ $dispo->catatan }}"
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @php 
                                    $pegawaiTampil = $tab === 'masuk' ? $dispo->dariPegawai : $dispo->kepadaPegawai; 
                                @endphp
                                
                                @if($pegawaiTampil)
                                    @if($pegawaiTampil->foto)
                                        <img src="{{ asset('storage/' . $pegawaiTampil->foto) }}" alt="Avatar" class="rounded-circle" width="32" height="32" style="object-fit: cover;">
                                    @else
                                        <div class="user-avatar shadow-sm" style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ $pegawaiTampil->inisials }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-medium text-dark" style="font-size: 0.85rem;">{{ Str::limit($pegawaiTampil->nama, 20) }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">{{ $pegawaiTampil->jabatan }}</div>
                                    </div>
                                @else
                                    <span class="text-muted font-italic">Pegawai telah dihapus</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="small fw-medium">{{ $dispo->created_at->format('d M Y, H:i') }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $dispo->created_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $dispo->status_badge }} px-2 py-1">{{ ucfirst($dispo->status) }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('disposisi.show', $dispo) }}" class="btn btn-sm btn-light border text-primary px-3 rounded-pill">
                                Lihat <i class="bi bi-chevron-right ms-1" style="font-size: 10px;"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state py-5">
                                <i class="bi bi-inbox mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                <h6>Tidak ada disposisi {{ $tab }} ditemukan</h6>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE VIEW (Cards) --}}
        <div class="d-md-none">
            @forelse($disposisi as $dispo)
            <div class="p-3 border-bottom {{ $tab === 'masuk' && $dispo->status === 'pending' ? 'bg-warning bg-opacity-10' : '' }}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-{{ $dispo->status_badge }}">{{ ucfirst($dispo->status) }}</span>
                    <span class="small text-muted">{{ $dispo->created_at->diffForHumans() }}</span>
                </div>
                
                <a href="{{ route('disposisi.show', $dispo) }}" class="text-decoration-none text-dark fw-bold d-block mb-2">
                    {{ $dispo->kegiatan->judul }}
                </a>
                
                <div class="bg-light p-2 rounded-2 small text-muted fst-italic mb-3">
                    "{{ Str::limit($dispo->catatan, 100) }}"
                </div>
                
                <div class="d-flex justify-content-between align-items-end">
                    <div class="d-flex align-items-center gap-2">
                        <div class="text-muted small" style="font-size: 0.7rem;">{{ $tab === 'masuk' ? 'Dari:' : 'Kepada:' }}</div>
                        @php 
                            $pegawaiTampil = $tab === 'masuk' ? $dispo->dariPegawai : $dispo->kepadaPegawai; 
                        @endphp
                        @if($pegawaiTampil)
                            @if($pegawaiTampil->foto)
                                <img src="{{ asset('storage/' . $pegawaiTampil->foto) }}" alt="Avatar" class="rounded-circle" width="24" height="24" style="object-fit: cover;">
                            @else
                                <div class="user-avatar shadow-sm bg-secondary text-white" style="width: 24px; height: 24px; font-size: 10px;">
                                    {{ $pegawaiTampil->inisials }}
                                </div>
                            @endif
                            <div class="fw-medium text-dark small" style="font-size: 0.8rem;">{{ Str::limit($pegawaiTampil->nama, 15) }}</div>
                        @endif
                    </div>
                    
                    <a href="{{ route('disposisi.show', $dispo) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                        Lihat <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="empty-state py-5">
                <i class="bi bi-inbox mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                <h6>Tidak ada disposisi {{ $tab }} ditemukan</h6>
            </div>
            @endforelse
        </div>
    </div>
    @if($disposisi->hasPages())
    <div class="card-footer bg-white border-top-0 pt-3">
        {{ $disposisi->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
