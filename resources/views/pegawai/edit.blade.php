@extends('layouts.app')

@section('title', 'Edit Pegawai: ' . $pegawai->nama)

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Pegawai</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pegawai.index') }}" class="text-decoration-none">Pegawai</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pegawai.show', $pegawai) }}" class="text-decoration-none">Profil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('pegawai.show', $pegawai) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <form action="{{ route('pegawai.update', $pegawai) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <!-- Kolom Kiri: Form Data -->
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white pb-0 border-bottom-0 mt-3 px-4">
                            <h5 class="fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i> Form Edit Data Pegawai</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip', $pegawai->nip) }}" required>
                                    @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $pegawai->nama) }}" required>
                                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan', $pegawai->jabatan) }}" required>
                                    @error('jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="pangkat_golongan" class="form-label">Pangkat/Golongan</label>
                                    <input type="text" class="form-control @error('pangkat_golongan') is-invalid @enderror" id="pangkat_golongan" name="pangkat_golongan" value="{{ old('pangkat_golongan', $pegawai->pangkat_golongan) }}">
                                    @error('pangkat_golongan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="unit_kerja" class="form-label">Unit Kerja</label>
                                    <input type="text" class="form-control @error('unit_kerja') is-invalid @enderror" id="unit_kerja" name="unit_kerja" value="{{ old('unit_kerja', $pegawai->unit_kerja) }}">
                                    @error('unit_kerja') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="no_hp" class="form-label">No. Handphone</label>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp', $pegawai->no_hp) }}">
                                    @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="status" class="form-label">Status Kepegawaian <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="aktif" {{ old('status', $pegawai->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $pegawai->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kolom Kanan: Foto -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white pb-0 border-bottom-0 mt-2 px-4">
                            <h6 class="fw-bold"><i class="bi bi-camera me-2"></i> Foto Profil</h6>
                        </div>
                        <div class="card-body p-4 text-center">
                            <div class="mb-3 d-flex justify-content-center">
                                @if($pegawai->foto)
                                    <img id="preview-foto" src="{{ asset('storage/' . $pegawai->foto) }}" class="rounded-circle border shadow-sm" width="120" height="120" style="object-fit: cover;">
                                @else
                                    <img id="preview-foto" src="{{ asset('img/default-avatar.png') }}" class="rounded-circle border shadow-sm" width="120" height="120" style="object-fit: cover; background: #f8f9fa;">
                                @endif
                            </div>
                            <input class="form-control form-control-sm @error('foto') is-invalid @enderror" id="foto" name="foto" type="file" accept=".jpg,.jpeg,.png">
                            <div class="form-text small mt-2 text-muted">Format: JPG, PNG. Maks. 2MB. Kosongkan jika tidak ingin mengubah foto.</div>
                            @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <!-- Info Akun -->
                    <div class="card shadow-sm border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-info-circle text-primary me-2 fs-5"></i>
                                <h6 class="fw-bold mb-0">Informasi Akun</h6>
                            </div>
                            @if($pegawai->user)
                                <p class="small mb-1">Pegawai ini memiliki akun dengan email: <br><strong>{{ $pegawai->user->email }}</strong></p>
                                <p class="small mb-0 text-muted fst-italic">Perubahan nama pada form ini akan otomatis mengupdate nama pada akun.</p>
                            @else
                                <p class="small mb-0 text-muted text-center py-2">Pegawai ini belum memiliki akun login.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4 shadow-sm border-0">
                <div class="card-body text-end p-3 bg-white rounded border-top">
                    <a href="{{ route('pegawai.show', $pegawai) }}" class="btn btn-light border px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 ms-2"><i class="bi bi-save me-1"></i> Update Data Pegawai</button>
                </div>
            </div>
            
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview
        const fotoInput = document.getElementById('foto');
        const previewFoto = document.getElementById('preview-foto');
        
        fotoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewFoto.setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
