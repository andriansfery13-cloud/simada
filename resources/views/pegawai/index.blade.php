@extends('layouts.app')

@section('title', 'Data Pegawai')

@section('content')
<div class="page-header">
    <div>
        <h1>Data Pegawai</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pegawai</li>
            </ol>
        </nav>
    </div>
    @if(auth()->check() && auth()->user()->isAdmin())
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-arrow-up me-1"></i> Import Data
        </button>
        <a href="{{ route('pegawai.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pegawai
        </a>
    </div>
    @endif
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('pegawai.index') }}" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama, NIP, atau jabatan..." value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="col-md-3">
                <select name="unit_kerja" class="form-select">
                    <option value="">Semua Unit Kerja</option>
                    @foreach($unitKerja as $unit)
                        <option value="{{ $unit }}" {{ request('unit_kerja') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-funnel me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    @forelse($pegawai as $peg)
    <div class="col-md-6 col-xl-4">
        <div class="card h-100 border-0 shadow-sm pegawai-card">
            <div class="card-body position-relative">
                <!-- Dropdown Aksi -->
                @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->id === $peg->user_id))
                <div class="dropdown position-absolute" style="top: 15px; right: 15px;">
                    <button class="btn btn-sm btn-light border-0 shadow-none" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="{{ route('pegawai.edit', $peg) }}"><i class="bi bi-pencil me-2 text-warning"></i> Edit</a></li>
                        @if(auth()->user()->isAdmin())
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('pegawai.destroy', $peg) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pegawai ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i> Hapus</button>
                            </form>
                        </li>
                        @endif
                    </ul>
                </div>
                @endif
                
                <div class="text-center mb-4 mt-3">
                    <div class="position-relative d-inline-block">
                        @if($peg->foto)
                            <img src="{{ asset('storage/' . $peg->foto) }}" alt="{{ $peg->nama }}" class="rounded-circle shadow-sm border border-3 border-white" width="90" height="90" style="object-fit: cover;">
                        @else
                            <div class="user-avatar shadow-sm border border-3 border-white d-flex justify-content-center align-items-center mx-auto" style="width: 90px; height: 90px; font-size: 32px; background: var(--gradient-primary); color: white;">
                                {{ $peg->inisials }}
                            </div>
                        @endif
                        <span class="position-absolute bottom-0 end-0 translate-middle p-2 bg-{{ $peg->status == 'aktif' ? 'success' : 'danger' }} border border-light rounded-circle" title="Status: {{ ucfirst($peg->status) }}">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                    </div>
                    <h5 class="fw-bold mt-3 mb-1 text-primary">{{ $peg->nama }}</h5>
                    <div class="text-muted small">{{ $peg->nip }}</div>
                </div>
                
                <div class="bg-light rounded-3 p-3 mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-briefcase text-secondary me-2"></i>
                        <span class="fw-medium small">{{ $peg->jabatan }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-building text-secondary me-2"></i>
                        <span class="text-muted small">{{ $peg->unit_kerja ?? 'Belum ada unit kerja' }}</span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between text-muted small mt-auto">
                    <div class="d-flex align-items-center gap-1">
                        <i class="bi bi-telephone"></i>
                        <span>{{ $peg->no_hp ?? '-' }}</span>
                    </div>
                    @if($peg->user)
                    <div class="d-flex align-items-center gap-1">
                        <i class="bi bi-shield-check text-success"></i>
                        <span>Punya Akun</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer bg-white border-top p-0">
                <a href="{{ route('pegawai.show', $peg) }}" class="btn btn-light w-100 py-3 text-primary fw-medium border-0 rounded-bottom-3 btn-detail">
                    Lihat Profil <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state py-5 card border-0 shadow-sm">
            <i class="bi bi-people mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
            <h6>Tidak ada data pegawai ditemukan</h6>
            <p class="text-muted small">Silakan sesuaikan filter pencarian.</p>
        </div>
    </div>
    @endforelse
</div>

@if($pegawai->hasPages())
<div class="mt-4">
    {{ $pegawai->withQueryString()->links() }}
</div>
@endif

{{-- ===== Modal Import Pegawai ===== --}}
@if(auth()->check() && auth()->user()->isAdmin())
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            {{-- Modal Header --}}
            <div class="modal-header border-0 pb-0" style="background: var(--gradient-primary); padding: 28px 28px 40px;">
                <div>
                    <h5 class="modal-title fw-bold text-white" id="importModalLabel">
                        <i class="bi bi-file-earmark-arrow-up me-2"></i> Import Data Pegawai
                    </h5>
                    <p class="text-white-50 small mb-0 mt-1">Upload file Excel/CSV untuk import data pegawai secara massal</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('pegawai.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="modal-body px-4 pt-0" style="margin-top: -20px;">
                    
                    {{-- Upload Zone --}}
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                        <div class="card-body p-4">
                            <div class="upload-zone text-center p-4 rounded-3 position-relative" id="uploadZone"
                                 style="border: 2px dashed var(--border-color); background: rgba(99,102,241,0.02); cursor: pointer; transition: all 0.3s;">
                                <input type="file" name="file" id="importFile" accept=".xlsx,.xls,.csv" class="position-absolute w-100 h-100 top-0 start-0" style="opacity: 0; cursor: pointer;" required>
                                <div id="uploadPlaceholder">
                                    <div class="mb-3" style="font-size: 48px; line-height: 1;">
                                        <i class="bi bi-cloud-arrow-up" style="background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">Pilih atau seret file ke sini</h6>
                                    <p class="text-muted small mb-2">Format yang didukung: <strong>.xlsx, .xls, .csv</strong></p>
                                    <p class="text-muted small mb-0">Maksimal ukuran file: <strong>5MB</strong></p>
                                </div>
                                <div id="uploadFileInfo" class="d-none">
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        <div class="file-icon-wrap d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: 14px; background: var(--gradient-success);">
                                            <i class="bi bi-file-earmark-spreadsheet text-white" style="font-size: 28px;"></i>
                                        </div>
                                        <div class="text-start">
                                            <h6 class="fw-bold mb-0" id="fileName">-</h6>
                                            <small class="text-muted" id="fileSize">-</small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-circle ms-2" id="removeFile" style="width: 32px; height: 32px; padding: 0;">
                                            <i class="bi bi-x-lg" style="font-size: 12px;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Opsi Akun --}}
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 12px; background: rgba(14,165,233,0.1);">
                                    <i class="bi bi-person-plus text-info" style="font-size: 18px;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-0">Buat Akun Otomatis</h6>
                                    <p class="text-muted small mb-0">Buat akun login untuk setiap pegawai yang memiliki email</p>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="buat_akun" value="1" id="buatAkunSwitch" style="width: 48px; height: 24px;">
                                </div>
                            </div>

                            <div id="akunOptions" class="d-none">
                                <hr class="my-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-shield-lock me-1"></i> Role Default
                                        </label>
                                        <select name="default_role" class="form-select">
                                            <option value="staff" selected>Staff</option>
                                            <option value="kasubag">Kasubag</option>
                                            <option value="umpeg">Umpeg</option>
                                            <option value="camat">Camat</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                        <div class="form-text">Role yang digunakan jika tidak ada kolom <code>role</code> di file</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-key me-1"></i> Password Default
                                        </label>
                                        <div class="input-group">
                                            <input type="text" name="default_password" class="form-control" value="password" id="defaultPasswordInput">
                                            <button type="button" class="btn btn-outline-secondary" id="togglePasswordVisibility" title="Tampilkan/Sembunyikan password">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">Password awal untuk semua akun yang dibuat</div>
                                    </div>
                                </div>

                                <div class="alert mt-3 mb-0 py-2 px-3" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2); border-radius: 10px;">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="bi bi-exclamation-triangle text-warning mt-1"></i>
                                        <small class="text-muted">
                                            Akun hanya dibuat untuk baris yang memiliki kolom <strong>email</strong> yang valid dan belum terdaftar di sistem. Pegawai yang sudah memiliki akun akan otomatis di-skip.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Template & Panduan --}}
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; background: linear-gradient(135deg, rgba(99,102,241,0.03) 0%, rgba(139,92,246,0.03) 100%);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start gap-3">
                                <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; border-radius: 12px; background: rgba(99,102,241,0.1);">
                                    <i class="bi bi-info-circle text-primary" style="font-size: 18px;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-2">Format File Import</h6>
                                    <p class="text-muted small mb-2">
                                        File harus memiliki header di baris pertama dengan kolom berikut:
                                    </p>
                                    <div class="d-flex flex-wrap gap-1 mb-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">nip <span class="text-danger">*</span></span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">nama <span class="text-danger">*</span></span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">jabatan <span class="text-danger">*</span></span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">pangkat_golongan</span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">unit_kerja</span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">no_hp</span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">status</span>
                                        <span class="badge bg-info bg-opacity-10 text-info">email</span>
                                        <span class="badge bg-info bg-opacity-10 text-info">role</span>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        <span class="text-danger">*</span> = Wajib diisi &nbsp;&bull;&nbsp; Kolom <code>email</code> & <code>role</code> digunakan untuk pembuatan akun
                                    </p>
                                    <a href="{{ route('pegawai.import.template') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-download me-1"></i> Download Template CSV
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-2">
                    <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill" id="importBtn" disabled>
                        <i class="bi bi-upload me-1"></i> Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
    .pegawai-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .pegawai-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .btn-detail {
        background-color: transparent;
        transition: background-color 0.2s;
    }
    .btn-detail:hover {
        background-color: rgba(99, 102, 241, 0.05);
    }
    .upload-zone:hover, .upload-zone.dragover {
        border-color: var(--primary) !important;
        background: rgba(99,102,241,0.06) !important;
    }
    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    #importModal .modal-content {
        backdrop-filter: blur(10px);
    }
    #importBtn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .import-spinner {
        display: none;
    }
    .importing .import-spinner {
        display: inline-block;
    }
    .importing .import-text {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('importFile');
    const uploadZone = document.getElementById('uploadZone');
    const placeholder = document.getElementById('uploadPlaceholder');
    const fileInfo = document.getElementById('uploadFileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeBtn = document.getElementById('removeFile');
    const importBtn = document.getElementById('importBtn');
    const buatAkunSwitch = document.getElementById('buatAkunSwitch');
    const akunOptions = document.getElementById('akunOptions');
    const importForm = document.getElementById('importForm');

    if (!fileInput) return;

    // File selection
    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            showFileInfo(this.files[0]);
        }
    });

    // Drag and drop
    uploadZone.addEventListener('dragover', function (e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    uploadZone.addEventListener('dragleave', function () {
        this.classList.remove('dragover');
    });
    uploadZone.addEventListener('drop', function (e) {
        e.preventDefault();
        this.classList.remove('dragover');
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            showFileInfo(e.dataTransfer.files[0]);
        }
    });

    function showFileInfo(file) {
        const validTypes = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
            'text/csv',
            'application/csv',
        ];
        const ext = file.name.split('.').pop().toLowerCase();
        if (!['xlsx', 'xls', 'csv'].includes(ext)) {
            alert('Format file tidak didukung. Gunakan .xlsx, .xls, atau .csv');
            fileInput.value = '';
            return;
        }
        fileName.textContent = file.name;
        fileSize.textContent = formatBytes(file.size);
        placeholder.classList.add('d-none');
        fileInfo.classList.remove('d-none');
        importBtn.disabled = false;
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Remove file
    removeBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        fileInput.value = '';
        placeholder.classList.remove('d-none');
        fileInfo.classList.add('d-none');
        importBtn.disabled = true;
    });

    // Toggle akun options
    buatAkunSwitch.addEventListener('change', function () {
        if (this.checked) {
            akunOptions.classList.remove('d-none');
            akunOptions.style.animation = 'fadeInUp 0.3s ease forwards';
        } else {
            akunOptions.classList.add('d-none');
        }
    });

    // Form submit loading state
    importForm.addEventListener('submit', function () {
        importBtn.disabled = true;
        importBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengimport...';
    });
});
</script>
@endpush

