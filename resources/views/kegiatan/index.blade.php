@extends('layouts.app')

@section('title', 'Daftar Kegiatan')

@section('content')
<div class="page-header">
    <div>
        <h1>Daftar Kegiatan</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kegiatan</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('kegiatan.kalender') }}" class="btn btn-outline-primary">
            <i class="bi bi-calendar3 me-1"></i> Kalender View
        </a>
        @if(auth()->user()->role !== 'staff')
        <a href="{{ route('kegiatan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Buat Kegiatan
        </a>
        @endif
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('kegiatan.index') }}" class="row g-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari judul..." value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="col-md-2">
                <select name="kategori_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="batal" {{ request('status') === 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <input type="date" name="tanggal_dari" class="form-control" placeholder="Dari Tanggal" value="{{ request('tanggal_dari') }}">
            </div>
            
            <div class="col-md-2">
                <input type="date" name="tanggal_sampai" class="form-control" placeholder="Sampai Tanggal" value="{{ request('tanggal_sampai') }}">
            </div>
            
            <div class="col-md-1 d-grid">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-filter"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        {{-- DESKTOP VIEW (Table) --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="35%">Informasi Kegiatan</th>
                        <th width="20%">Waktu & Tempat</th>
                        <th width="15%">Kategori</th>
                        <th width="10%">Peserta</th>
                        <th width="10%">Status</th>
                        <th width="10%" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatan as $keg)
                    <tr>
                        <td>
                            <div class="fw-bold mb-1">
                                <a href="{{ route('kegiatan.show', $keg) }}" class="text-decoration-none text-primary">{{ $keg->judul }}</a>
                                <span class="badge bg-{{ $keg->prioritas_badge }} ms-2" style="font-size: 0.65rem;">{{ strtoupper($keg->prioritas) }}</span>
                            </div>
                            <div class="text-muted small text-truncate" style="max-width: 350px;">
                                {{ $keg->deskripsi ?: 'Tidak ada deskripsi' }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <div class="small fw-medium"><i class="bi bi-calendar-event text-secondary me-1"></i> {{ $keg->tanggal->format('d M Y') }}</div>
                                <div class="small"><i class="bi bi-clock text-secondary me-1"></i> {{ \Carbon\Carbon::parse($keg->jam_mulai)->format('H:i') }} - {{ $keg->jam_selesai ? \Carbon\Carbon::parse($keg->jam_selesai)->format('H:i') : 'Selesai' }}</div>
                                <div class="small text-truncate" style="max-width: 200px;"><i class="bi bi-geo-alt text-secondary me-1"></i> {{ $keg->tempat }}</div>
                            </div>
                        </td>
                        <td>
                            @if($keg->kategori)
                                <span class="badge" style="background-color: {{ $keg->kategori->warna }}">
                                    <i class="bi {{ $keg->kategori->icon }} me-1"></i> {{ $keg->kategori->nama }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Umum</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-light text-dark border"><i class="bi bi-people-fill me-1"></i> {{ $keg->peserta->count() }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $keg->status_badge }}">{{ ucfirst($keg->status) }}</span>
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li><a class="dropdown-item" href="{{ route('kegiatan.show', $keg) }}"><i class="bi bi-eye me-2 text-primary"></i> Detail</a></li>
                                    @if(auth()->user()->role !== 'staff')
                                    <li><a class="dropdown-item" href="{{ route('kegiatan.edit', $keg) }}"><i class="bi bi-pencil me-2 text-warning"></i> Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('kegiatan.destroy', $keg) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i> Hapus</button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state py-5">
                                <i class="bi bi-clipboard-x mb-3" style="font-size: 3rem;"></i>
                                <h6>Tidak ada data kegiatan ditemukan</h6>
                                <p class="text-muted small">Silakan sesuaikan filter atau buat kegiatan baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE VIEW (Cards) --}}
        <div class="d-md-none">
            @forelse($kegiatan as $keg)
            <div class="p-3 border-bottom position-relative">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div style="padding-right: 30px;">
                        <a href="{{ route('kegiatan.show', $keg) }}" class="text-decoration-none text-dark fw-bold d-block">{{ $keg->judul }}</a>
                        <div class="mt-1">
                            <span class="badge bg-{{ $keg->status_badge }} me-1">{{ ucfirst($keg->status) }}</span>
                            <span class="badge bg-{{ $keg->prioritas_badge }}">{{ strtoupper($keg->prioritas) }}</span>
                        </div>
                    </div>
                    <div class="dropdown position-absolute" style="top: 15px; right: 15px;">
                        <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="{{ route('kegiatan.show', $keg) }}"><i class="bi bi-eye me-2 text-primary"></i> Detail</a></li>
                            @if(auth()->user()->role !== 'staff')
                            <li><a class="dropdown-item" href="{{ route('kegiatan.edit', $keg) }}"><i class="bi bi-pencil me-2 text-warning"></i> Edit</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('kegiatan.destroy', $keg) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i> Hapus</button>
                                </form>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                
                <div class="d-flex flex-column gap-1 mt-3">
                    <div class="small"><i class="bi bi-calendar-event text-secondary me-2"></i> {{ $keg->tanggal->format('d M Y') }}</div>
                    <div class="small"><i class="bi bi-clock text-secondary me-2"></i> {{ \Carbon\Carbon::parse($keg->jam_mulai)->format('H:i') }} - {{ $keg->jam_selesai ? \Carbon\Carbon::parse($keg->jam_selesai)->format('H:i') : 'Selesai' }}</div>
                    <div class="small"><i class="bi bi-geo-alt text-secondary me-2"></i> {{ $keg->tempat }}</div>
                    
                    <div class="d-flex align-items-center mt-2 gap-2">
                        @if($keg->kategori)
                            <span class="badge" style="background-color: {{ $keg->kategori->warna }}; font-size: 0.65rem;">
                                <i class="bi {{ $keg->kategori->icon }} me-1"></i> {{ $keg->kategori->nama }}
                            </span>
                        @endif
                        <span class="badge bg-light text-dark border" style="font-size: 0.65rem;"><i class="bi bi-people-fill me-1"></i> {{ $keg->peserta->count() }} Peserta</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state py-5">
                <i class="bi bi-clipboard-x mb-3" style="font-size: 3rem;"></i>
                <h6>Tidak ada data kegiatan ditemukan</h6>
                <p class="text-muted small">Silakan sesuaikan filter atau buat kegiatan baru.</p>
            </div>
            @endforelse
        </div>
    </div>
    @if($kegiatan->hasPages())
    <div class="card-footer bg-white border-top-0 pt-3">
        {{ $kegiatan->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
