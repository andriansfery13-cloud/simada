@extends('layouts.app')

@section('title', 'Cetak Laporan')

@section('content')
<div class="page-header">
    <div>
        <h1>Laporan Agenda Kegiatan</h1>
        <p class="text-secondary mb-0">Cetak laporan kegiatan berdasarkan filter bulan dan status ke format PDF.</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white p-4 text-center rounded-top-3" style="background: var(--gradient-primary) !important;">
                <i class="bi bi-file-earmark-pdf fs-1 d-block mb-2"></i>
                <h4 class="mb-0 fw-bold">Generator Laporan PDF</h4>
            </div>
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('laporan.generate') }}" method="POST" target="_blank">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary text-uppercase small" style="letter-spacing: 1px;">Periode Bulan <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar3"></i></span>
                            <input type="month" name="bulan" class="form-control border-start-0 ps-0" required value="{{ date('Y-m') }}">
                        </div>
                        <div class="form-text mt-2">Pilih bulan dan tahun kegiatan.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary text-uppercase small" style="letter-spacing: 1px;">Status Kegiatan</label>
                        <select name="status" class="form-select form-select-lg">
                            <option value="">Semua Status (Aktif & Selesai)</option>
                            <option value="aktif">Hanya Aktif</option>
                            <option value="selesai">Hanya Selesai</option>
                            <option value="batal">Batal</option>
                        </select>
                    </div>
                    
                    <div class="mb-5">
                        <label class="form-label fw-bold text-secondary text-uppercase small" style="letter-spacing: 1px;">Pengurutan</label>
                        <select name="sort" class="form-select form-select-lg">
                            <option value="asc">Tanggal Paling Awal (Lama ke Baru)</option>
                            <option value="desc">Tanggal Terakhir (Baru ke Lama)</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger btn-lg py-3 fw-bold rounded-pill shadow-sm">
                            <i class="bi bi-printer me-2 fs-5 align-middle"></i> Generate Laporan PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="alert alert-info mt-4 d-flex border-0 shadow-sm rounded-4 p-4">
            <i class="bi bi-info-circle-fill fs-3 text-info me-3 mt-1"></i>
            <div>
                <h6 class="fw-bold mb-1 text-dark">Informasi Cetak</h6>
                <p class="mb-0 small text-secondary lh-lg">Laporan yang dihasilkan berupa file PDF dengan orientasi Landscape yang berisi rekapitulasi data kegiatan, waktu pelaksanaan, status, dan jumlah peserta berdasarkan bulan yang dipilih. Laporan juga dilengkapi kolom tanda tangan digital.</p>
            </div>
        </div>
    </div>
</div>
@endsection
