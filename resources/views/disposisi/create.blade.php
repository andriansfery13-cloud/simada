@extends('layouts.app')

@section('title', 'Buat Disposisi')

@section('content')
<div class="page-header">
    <div>
        <h1>Buat Disposisi</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('disposisi.index') }}" class="text-decoration-none">Disposisi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Buat Baru</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pb-0 mt-3 px-4">
                <h5 class="fw-bold text-primary mb-0"><i class="bi bi-send me-2"></i> Form Disposisi</h5>
            </div>
            
            <div class="card-body p-4">
                @if(!auth()->user()->pegawai)
                <div class="alert alert-warning d-flex align-items-center mb-4">
                    <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Akses Terbatas</h6>
                        <p class="mb-0 small">Akun Anda belum dihubungkan dengan data Pegawai. Silakan hubungi Administrator untuk mengatur ini agar Anda dapat membuat disposisi.</p>
                    </div>
                </div>
                @else
                
                <form action="{{ route('disposisi.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="kegiatan_id" class="form-label fw-semibold">Pilih Kegiatan <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg shadow-sm @error('kegiatan_id') is-invalid @enderror" id="kegiatan_id" name="kegiatan_id" required>
                            <option value="">Pilih kegiatan...</option>
                            @foreach($kegiatan as $keg)
                                <option value="{{ $keg->id }}" {{ (old('kegiatan_id') == $keg->id) || ($selectedKegiatan && $selectedKegiatan->id == $keg->id) ? 'selected' : '' }}>
                                    {{ $keg->judul }} ({{ $keg->tanggal->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('kegiatan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pilih Penerima Disposisi <span class="text-danger">*</span></label>
                        <div class="card border border-primary-subtle shadow-sm rounded-3">
                            <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center p-3">
                                <span class="fw-medium text-secondary" style="font-size: 0.9rem;">Daftar Pegawai</span>
                                <input type="text" id="search-pegawai" class="form-control form-control-sm w-50" placeholder="Cari nama...">
                            </div>
                            <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                                <div class="list-group list-group-flush" id="pegawai-list">
                                    @foreach($pegawai as $peg)
                                        @if($peg->id !== auth()->user()->pegawai->id) <!-- Sembunyikan diri sendiri -->
                                        <label class="list-group-item list-group-item-action d-flex align-items-center p-3 pegawai-item cursor-pointer">
                                            <input class="form-check-input flex-shrink-0 mt-0 me-3 shadow-none rounded-circle border-2" style="width: 1.25rem; height: 1.25rem;" type="radio" name="kepada_pegawai_id" value="{{ $peg->id }}" {{ old('kepada_pegawai_id') == $peg->id ? 'checked' : '' }} required>
                                            
                                            <div class="d-flex align-items-center flex-grow-1">
                                                @if($peg->foto)
                                                    <img src="{{ asset('storage/' . $peg->foto) }}" alt="Avatar" class="rounded-circle me-3 border" width="40" height="40" style="object-fit: cover;">
                                                @else
                                                    <div class="user-avatar shadow-sm me-3" style="width: 40px; height: 40px; font-size: 14px;">
                                                        {{ $peg->inisials }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold text-dark pegawai-nama">{{ $peg->nama }}</div>
                                                    <div class="text-secondary small">{{ $peg->jabatan }} • {{ $peg->unit_kerja }}</div>
                                                </div>
                                            </div>
                                        </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('kepada_pegawai_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="catatan" class="form-label fw-semibold">Isi Disposisi / Catatan Arahan <span class="text-danger">*</span></label>
                        <textarea class="form-control shadow-sm @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="5" placeholder="Tuliskan arahan, tugas, atau catatan khusus untuk kegiatan ini..." required>{{ old('catatan') }}</textarea>
                        @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <hr class="my-4 text-muted">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-light border px-4">Reset</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="bi bi-send-fill me-1"></i> Kirim Disposisi</button>
                    </div>
                </form>
                
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('search-pegawai');
        const pegawaiItems = document.querySelectorAll('.pegawai-item');
        
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const term = this.value.toLowerCase();
                
                pegawaiItems.forEach(item => {
                    const name = item.querySelector('.pegawai-nama').textContent.toLowerCase();
                    if (name.includes(term)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
        
        // Style selected radio
        const radios = document.querySelectorAll('input[name="kepada_pegawai_id"]');
        
        function updateStyles() {
            radios.forEach(radio => {
                const label = radio.closest('label');
                if (radio.checked) {
                    label.classList.add('bg-primary-subtle');
                } else {
                    label.classList.remove('bg-primary-subtle');
                }
            });
        }
        
        radios.forEach(radio => {
            radio.addEventListener('change', updateStyles);
        });
        
        updateStyles(); // Initial
    });
</script>
@endpush
