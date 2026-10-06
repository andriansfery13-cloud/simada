@extends('layouts.app')

@section('title', 'Edit Kegiatan')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Kegiatan</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Kegiatan</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kegiatan.show', $kegiatan) }}" class="text-decoration-none">Detail</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('kegiatan.show', $kegiatan) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header bg-white pb-0 border-bottom-0 mt-3">
                <h5 class="fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i> Form Edit Kegiatan</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('kegiatan.update', $kegiatan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="judul" class="form-label">Judul Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul', $kegiatan->judul) }}" required>
                        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select class="form-select @error('kategori_id') is-invalid @enderror" id="kategori_id" name="kategori_id">
                                <option value="">Pilih Kategori...</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}" {{ old('kategori_id', $kegiatan->kategori_id) == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                                @endforeach
                            </select>
                            @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="prioritas" class="form-label">Prioritas <span class="text-danger">*</span></label>
                            <select class="form-select @error('prioritas') is-invalid @enderror" id="prioritas" name="prioritas" required>
                                <option value="rendah" {{ old('prioritas', $kegiatan->prioritas) == 'rendah' ? 'selected' : '' }}>Rendah (Info)</option>
                                <option value="sedang" {{ old('prioritas', $kegiatan->prioritas) == 'sedang' ? 'selected' : '' }}>Sedang (Normal)</option>
                                <option value="tinggi" {{ old('prioritas', $kegiatan->prioritas) == 'tinggi' ? 'selected' : '' }}>Tinggi (Penting)</option>
                            </select>
                            @error('prioritas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', $kegiatan->tanggal->format('Y-m-d')) }}" required>
                            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', \Carbon\Carbon::parse($kegiatan->jam_mulai)->format('H:i')) }}" required>
                            @error('jam_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="jam_selesai" class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', $kegiatan->jam_selesai ? \Carbon\Carbon::parse($kegiatan->jam_selesai)->format('H:i') : '') }}">
                            <div class="form-text">Kosongkan jika waktu tentatif.</div>
                            @error('jam_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="tempat" class="form-label">Tempat/Lokasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tempat') is-invalid @enderror" id="tempat" name="tempat" value="{{ old('tempat', $kegiatan->tempat) }}" required>
                        @error('tempat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="deskripsi" class="form-label">Deskripsi Agenda</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
                        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Peserta Terlibat</label>
                        <div class="card border">
                            <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
                                <div class="list-group list-group-flush" id="peserta-list">
                                    @foreach($pegawai as $peg)
                                    <label class="list-group-item d-flex gap-3 align-items-center py-3">
                                        <input class="form-check-input flex-shrink-0 fs-5 mt-0" type="checkbox" name="peserta[]" value="{{ $peg->id }}" {{ in_array($peg->id, old('peserta', $selectedPeserta)) ? 'checked' : '' }}>
                                        <span class="pt-1 form-checked-content">
                                            <span class="fw-bold d-block">{{ $peg->nama }}</span>
                                            <span class="d-block text-secondary small">{{ $peg->jabatan }} - {{ $peg->unit_kerja }}</span>
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-footer bg-light py-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="check-all-peserta">
                                    <label class="form-check-label fw-semibold" for="check-all-peserta">
                                        Pilih Semua Pegawai
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="draft" {{ old('status', $kegiatan->status) == 'draft' ? 'selected' : '' }}>Draft (Belum Dipublikasi)</option>
                            <option value="aktif" {{ old('status', $kegiatan->status) == 'aktif' ? 'selected' : '' }}>Aktif (Terjadwal)</option>
                            <option value="selesai" {{ old('status', $kegiatan->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ old('status', $kegiatan->status) == 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('kegiatan.show', $kegiatan) }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Update Kegiatan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('check-all-peserta');
        const checkboxes = document.querySelectorAll('input[name="peserta[]"]');
        
        // Initial check
        const allChecked = Array.from(checkboxes).every(c => c.checked);
        const someChecked = Array.from(checkboxes).some(c => c.checked);
        
        checkAll.checked = allChecked;
        checkAll.indeterminate = someChecked && !allChecked;
        
        checkAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = checkAll.checked;
            });
        });
        
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                const someChecked = Array.from(checkboxes).some(c => c.checked);
                
                checkAll.checked = allChecked;
                checkAll.indeterminate = someChecked && !allChecked;
            });
        });
    });
</script>
@endpush
