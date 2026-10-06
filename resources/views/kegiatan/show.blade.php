@extends('layouts.app')

@section('title', 'Detail Kegiatan: ' . $kegiatan->judul)

@section('content')
<div class="page-header">
    <div>
        <h1>Detail Kegiatan</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Kegiatan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        @if(auth()->user()->role !== 'staff')
        <a href="{{ route('kegiatan.edit', $kegiatan) }}" class="btn btn-warning text-dark">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Detail Kegiatan -->
    <div class="col-lg-8">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white p-4 pb-0 border-0">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h3 class="mb-0 fw-bold text-primary">{{ $kegiatan->judul }}</h3>
                    <div class="d-flex gap-2">
                        <span class="badge bg-{{ $kegiatan->prioritas_badge }} fs-6">{{ strtoupper($kegiatan->prioritas) }}</span>
                        <span class="badge bg-{{ $kegiatan->status_badge }} fs-6">{{ ucfirst($kegiatan->status) }}</span>
                    </div>
                </div>
                
                <div class="d-flex flex-wrap gap-3 text-secondary mb-4">
                    @if($kegiatan->kategori)
                    <div class="d-flex align-items-center">
                        <span class="badge" style="background-color: {{ $kegiatan->kategori->warna }}">
                            <i class="bi {{ $kegiatan->kategori->icon }} me-1"></i> {{ $kegiatan->kategori->nama }}
                        </span>
                    </div>
                    @endif
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-event me-2 text-primary"></i>
                        <span class="fw-medium">{{ $kegiatan->tanggal->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock me-2 text-primary"></i>
                        <span class="fw-medium">{{ \Carbon\Carbon::parse($kegiatan->jam_mulai)->format('H:i') }} - {{ $kegiatan->jam_selesai ? \Carbon\Carbon::parse($kegiatan->jam_selesai)->format('H:i') : 'Selesai' }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-geo-alt me-2 text-danger"></i>
                        <span class="fw-medium">{{ $kegiatan->tempat }}</span>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4 border-top">
                <h6 class="fw-bold mb-3 text-secondary text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">Deskripsi Agenda</h6>
                <div class="bg-light rounded p-4 border" style="min-height: 100px;">
                    {!! nl2br(e($kegiatan->deskripsi ?: 'Tidak ada deskripsi yang ditambahkan.')) !!}
                </div>
                
                <div class="mt-4 pt-3 border-top text-muted small d-flex justify-content-between">
                    <div>
                        <i class="bi bi-person me-1"></i> Dibuat oleh: <strong>{{ $kegiatan->creator->name ?? 'Sistem' }}</strong>
                    </div>
                    <div>
                        <i class="bi bi-calendar-plus me-1"></i> Dibuat pada: {{ $kegiatan->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Dokumentasi Section -->
        <div class="card shadow-sm">
            <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-folder-check text-primary me-2"></i> Dokumentasi & Lampiran</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="bi bi-cloud-upload me-1"></i> Upload File
                </button>
            </div>
            <div class="card-body p-4">
                @if($kegiatan->dokumentasi->isEmpty())
                    <div class="empty-state py-4 text-center">
                        <i class="bi bi-file-earmark-x mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                        <h6 class="text-secondary">Belum ada dokumen yang diunggah</h6>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($kegiatan->dokumentasi as $dokumen)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border bg-light shadow-none">
                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        @if($dokumen->tipe == 'foto')
                                            <i class="bi bi-file-image text-primary fs-3"></i>
                                        @elseif($dokumen->tipe == 'dokumen')
                                            <i class="bi bi-file-earmark-text text-secondary fs-3"></i>
                                        @else
                                            <i class="bi bi-file-earmark-pdf text-danger fs-3"></i>
                                        @endif
                                        
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                <li><a class="dropdown-item" href="{{ asset('storage/' . $dokumen->file_path) }}" target="_blank"><i class="bi bi-download me-2"></i> Unduh / Buka</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('dokumentasi.destroy', $dokumen) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i> Hapus</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <h6 class="card-title text-truncate mb-1" title="{{ $dokumen->file_name }}" style="font-size: 0.9rem;">
                                        {{ $dokumen->file_name }}
                                    </h6>
                                    <div class="mt-auto pt-2">
                                        <span class="badge bg-secondary opacity-75 fw-normal">{{ ucfirst($dokumen->tipe) }}</span>
                                        <small class="text-muted ms-1" style="font-size: 0.7rem;">{{ $dokumen->created_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Kolom Kanan: Peserta & Disposisi -->
    <div class="col-lg-4">
        <!-- Disposisi Card -->
        @if(auth()->check() && auth()->user()->canDisposisi())
        <div class="card mb-4 shadow-sm border-warning">
            <div class="card-header bg-warning bg-opacity-10 text-dark p-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-envelope-paper me-2"></i> Disposisi Agenda</h6>
                <a href="{{ route('disposisi.create', ['kegiatan_id' => $kegiatan->id]) }}" class="btn btn-sm btn-warning">Buat</a>
            </div>
            <div class="card-body p-0">
                @if($kegiatan->disposisi->isEmpty())
                    <div class="p-4 text-center text-muted small">
                        Belum ada disposisi untuk kegiatan ini.
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($kegiatan->disposisi as $dispo)
                        <div class="list-group-item p-3 border-bottom-0 border-top">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold" style="font-size: 0.85rem;">Ke: {{ Str::limit($dispo->kepadaPegawai->nama, 20) }}</span>
                                <span class="badge bg-{{ $dispo->status_badge }}">{{ ucfirst($dispo->status) }}</span>
                            </div>
                            <p class="small text-muted mb-2 fst-italic">"{{ Str::limit($dispo->catatan, 60) }}"</p>
                            <div class="text-end">
                                <a href="{{ route('disposisi.show', $dispo) }}" class="btn btn-sm btn-link p-0 text-decoration-none" style="font-size: 0.8rem;">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Peserta Card -->
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-people-fill text-primary me-2"></i> Daftar Peserta ({{ $kegiatan->peserta->count() }})</h6>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#kehadiranModal" title="Update Kehadiran">
                    <i class="bi bi-check2-square"></i>
                </button>
            </div>
            <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                @if($kegiatan->peserta->isEmpty())
                    <div class="p-4 text-center text-muted small">
                        Tidak ada peserta yang terdaftar.
                    </div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($kegiatan->peserta as $peserta)
                        <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                @if($peserta->foto)
                                    <img src="{{ asset('storage/' . $peserta->foto) }}" alt="{{ $peserta->nama }}" class="rounded-circle" width="36" height="36" style="object-fit: cover;">
                                @else
                                    <div class="user-avatar" style="width: 36px; height: 36px; font-size: 14px;">
                                        {{ $peserta->inisials }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold" style="font-size: 0.9rem;">{{ $peserta->nama }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $peserta->jabatan }}</div>
                                </div>
                            </div>
                            
                            @php
                                $statusHadir = $peserta->pivot->status_kehadiran;
                                $badgeClass = match($statusHadir) {
                                    'hadir' => 'success',
                                    'izin' => 'warning',
                                    'alpa' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }} bg-opacity-25 text-{{ $badgeClass == 'warning' ? 'dark' : $badgeClass }} border border-{{ $badgeClass }} fw-medium">
                                {{ ucfirst($statusHadir) }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Dokumentasi -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Upload Dokumentasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dokumentasi.store', $kegiatan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipe Dokumen</label>
                        <select name="tipe" class="form-select" required>
                            <option value="dokumen">Dokumen Umum (PDF, DOCX)</option>
                            <option value="foto">Foto Kegiatan (JPG, PNG)</option>
                            <option value="notulen">Notulensi Rapat</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih File (Multiple)</label>
                        <input type="file" name="files[]" class="form-control" multiple required accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        <div class="form-text mt-2 text-danger">Max ukuran 5MB per file.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-upload me-1"></i> Upload Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Update Kehadiran -->
<div class="modal fade" id="kehadiranModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Update Status Kehadiran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kegiatan.kehadiran', $kegiatan) }}" method="POST">
                @csrf
                <div class="modal-body p-0">
                    {{-- DESKTOP VIEW --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nama Peserta</th>
                                    <th class="text-center" width="60%">Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kegiatan->peserta as $index => $peserta)
                                <tr>
                                    <td>
                                        <input type="hidden" name="kehadiran[{{ $index }}][pegawai_id]" value="{{ $peserta->id }}">
                                        <div class="fw-semibold" style="font-size: 0.9rem;">{{ $peserta->nama }}</div>
                                    </td>
                                    <td>
                                        @php $status = $peserta->pivot->status_kehadiran; @endphp
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" name="kehadiran[{{ $index }}][status]" id="btnradio1_{{ $index }}" value="belum" {{ $status == 'belum' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary btn-sm" for="btnradio1_{{ $index }}">Belum</label>
                                          
                                            <input type="radio" class="btn-check" name="kehadiran[{{ $index }}][status]" id="btnradio2_{{ $index }}" value="hadir" {{ $status == 'hadir' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-success btn-sm" for="btnradio2_{{ $index }}">Hadir</label>
                                          
                                            <input type="radio" class="btn-check" name="kehadiran[{{ $index }}][status]" id="btnradio3_{{ $index }}" value="izin" {{ $status == 'izin' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-warning btn-sm" for="btnradio3_{{ $index }}">Izin</label>
    
                                            <input type="radio" class="btn-check" name="kehadiran[{{ $index }}][status]" id="btnradio4_{{ $index }}" value="alpa" {{ $status == 'alpa' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger btn-sm" for="btnradio4_{{ $index }}">Alpa</label>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- MOBILE VIEW --}}
                    <div class="d-md-none p-2">
                        @foreach($kegiatan->peserta as $index => $peserta)
                        <div class="card shadow-sm mb-3 border-0">
                            <div class="card-body p-3">
                                <input type="hidden" name="kehadiran[{{ $index }}][pegawai_id]" value="{{ $peserta->id }}">
                                <div class="fw-semibold mb-2" style="font-size: 0.9rem;">{{ $peserta->nama }}</div>
                                
                                @php $status = $peserta->pivot->status_kehadiran; @endphp
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="flex-grow-1">
                                        <input type="radio" class="btn-check" name="kehadiran[{{ $index }}][status]" id="mob_btnradio1_{{ $index }}" value="belum" {{ $status == 'belum' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-secondary btn-sm w-100" for="mob_btnradio1_{{ $index }}">Belum</label>
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="radio" class="btn-check" name="kehadiran[{{ $index }}][status]" id="mob_btnradio2_{{ $index }}" value="hadir" {{ $status == 'hadir' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success btn-sm w-100" for="mob_btnradio2_{{ $index }}">Hadir</label>
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="radio" class="btn-check" name="kehadiran[{{ $index }}][status]" id="mob_btnradio3_{{ $index }}" value="izin" {{ $status == 'izin' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning btn-sm w-100" for="mob_btnradio3_{{ $index }}">Izin</label>
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="radio" class="btn-check" name="kehadiran[{{ $index }}][status]" id="mob_btnradio4_{{ $index }}" value="alpa" {{ $status == 'alpa' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger btn-sm w-100" for="mob_btnradio4_{{ $index }}">Alpa</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Kehadiran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
