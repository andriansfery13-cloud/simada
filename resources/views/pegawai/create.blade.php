@extends('layouts.app')

@section('title', 'Tambah Pegawai')

@section('content')
<div class="page-header">
    <div>
        <h1>Tambah Pegawai</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pegawai.index') }}" class="text-decoration-none">Pegawai</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <form action="{{ route('pegawai.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-4">
                <!-- Kolom Kiri: Form Data -->
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white pb-0 border-bottom-0 mt-3 px-4">
                            <h5 class="fw-bold text-primary"><i class="bi bi-person-lines-fill me-2"></i> Data Kepegawaian</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip') }}" required placeholder="Nomor Induk Pegawai">
                                    @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Nama beserta gelar">
                                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan') }}" required placeholder="Contoh: Kasi Pemerintahan">
                                    @error('jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="pangkat_golongan" class="form-label">Pangkat/Golongan</label>
                                    <input type="text" class="form-control @error('pangkat_golongan') is-invalid @enderror" id="pangkat_golongan" name="pangkat_golongan" value="{{ old('pangkat_golongan') }}" placeholder="Contoh: III/c - Penata">
                                    @error('pangkat_golongan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="unit_kerja" class="form-label">Unit Kerja</label>
                                    <input type="text" class="form-control @error('unit_kerja') is-invalid @enderror" id="unit_kerja" name="unit_kerja" value="{{ old('unit_kerja') }}" placeholder="Contoh: Seksi Pemerintahan">
                                    @error('unit_kerja') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="no_hp" class="form-label">No. Handphone</label>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890">
                                    @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="status" class="form-label">Status Kepegawaian <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kolom Kanan: Foto & Akun -->
                <div class="col-md-4">
                    <!-- Card Foto -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white pb-0 border-bottom-0 mt-2 px-4">
                            <h6 class="fw-bold"><i class="bi bi-camera me-2"></i> Foto Profil</h6>
                        </div>
                        <div class="card-body p-4 text-center">
                            <div class="mb-3 d-flex justify-content-center">
                                <img id="preview-foto" src="{{ asset('img/default-avatar.png') }}" class="rounded-circle border shadow-sm" width="120" height="120" style="object-fit: cover; background: #f8f9fa;">
                            </div>
                            <input class="form-control form-control-sm @error('foto') is-invalid @enderror" id="foto" name="foto" type="file" accept=".jpg,.jpeg,.png">
                            <div class="form-text small mt-2 text-muted">Format: JPG, PNG. Maksimal: 2MB</div>
                            @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <!-- Card Buat Akun -->
                    <div class="card shadow-sm border-0 border-primary border-top border-3">
                        <div class="card-header bg-white pb-0 border-bottom-0 mt-2 px-4">
                            <h6 class="fw-bold"><i class="bi bi-shield-lock me-2"></i> Buat Akun Login (Opsional)</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="toggleAkun" name="create_account" value="1" {{ old('create_account') ? 'checked' : '' }}>
                                <label class="form-check-label ms-1" for="toggleAkun">Buatkan Akun Pengguna</label>
                            </div>
                            
                            <div id="formAkun" style="display: {{ old('create_account') ? 'block' : 'none' }};">
                                <div class="mb-3">
                                    <label for="email" class="form-label small fw-medium">Alamat Email</label>
                                    <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="email@simada.test">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="mb-2">
                                    <label for="role" class="form-label small fw-medium">Hak Akses (Role)</label>
                                    <select class="form-select form-select-sm @error('role') is-invalid @enderror" id="role" name="role">
                                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="kasubag" {{ old('role') == 'kasubag' ? 'selected' : '' }}>Kasubag</option>
                                        <option value="umpeg" {{ old('role') == 'umpeg' ? 'selected' : '' }}>UMPEG / Sekretaris</option>
                                        <option value="camat" {{ old('role') == 'camat' ? 'selected' : '' }}>Camat</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    </select>
                                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-text text-warning small mt-2">
                                    <i class="bi bi-info-circle me-1"></i> Password default: <strong>password</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4 shadow-sm border-0">
                <div class="card-body text-end p-3 bg-light rounded">
                    <button type="reset" class="btn btn-secondary border">Reset Form</button>
                    <button type="submit" class="btn btn-primary px-4 ms-2"><i class="bi bi-save me-1"></i> Simpan Pegawai</button>
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
        
        // Toggle Akun form
        const toggleAkun = document.getElementById('toggleAkun');
        const formAkun = document.getElementById('formAkun');
        const emailInput = document.getElementById('email');
        const roleInput = document.getElementById('role');
        
        toggleAkun.addEventListener('change', function() {
            if (this.checked) {
                formAkun.style.display = 'block';
                emailInput.setAttribute('required', 'required');
            } else {
                formAkun.style.display = 'none';
                emailInput.removeAttribute('required');
                emailInput.value = '';
                roleInput.value = 'staff';
            }
        });
    });
</script>
@endpush
