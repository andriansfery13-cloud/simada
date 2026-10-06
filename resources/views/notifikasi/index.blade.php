@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="page-header">
    <div>
        <h1>Pemberitahuan</h1>
        <p class="text-secondary mb-0">Semua notifikasi terkait agenda, undangan, dan disposisi.</p>
    </div>
    
    @if($notifikasi->count() > 0)
    <div>
        <form action="{{ route('notifikasi.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca
            </button>
        </form>
    </div>
    @endif
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Riwayat Notifikasi Terbaru</h6>
            </div>
            <div class="card-body p-0">
                @if($notifikasi->isEmpty())
                    <div class="empty-state py-5">
                        <i class="bi bi-bell-slash mb-3" style="font-size: 4rem; color: #cbd5e1;"></i>
                        <h5>Belum Ada Pemberitahuan</h5>
                        <p class="text-muted">Anda tidak memiliki notifikasi terbaru saat ini.</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($notifikasi as $notif)
                        <div class="list-group-item list-group-item-action p-4 border-bottom {{ !$notif->dibaca ? 'bg-primary bg-opacity-10' : '' }}">
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-{{ $notif->tipe == 'undangan' ? 'success' : ($notif->tipe == 'disposisi' ? 'warning' : 'primary') }} text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 45px; height: 45px;">
                                    @if($notif->tipe == 'undangan')
                                        <i class="bi bi-calendar-check fs-5"></i>
                                    @elseif($notif->tipe == 'disposisi')
                                        <i class="bi bi-envelope-paper fs-5"></i>
                                    @else
                                        <i class="bi bi-bell fs-5"></i>
                                    @endif
                                </div>
                                
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="fw-bold mb-0 {{ !$notif->dibaca ? 'text-primary' : 'text-dark' }}">
                                            {{ $notif->judul }}
                                            @if(!$notif->dibaca)
                                                <span class="badge bg-danger rounded-pill ms-2" style="font-size: 0.6rem; vertical-align: top; margin-top: 2px;">BARU</span>
                                            @endif
                                        </h6>
                                        <small class="text-muted fst-italic" style="font-size: 0.75rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-2 text-secondary small">{{ $notif->pesan }}</p>
                                    
                                    @if($notif->link)
                                    <div>
                                        <form action="{{ route('notifikasi.read', $notif) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-medium" style="font-size: 0.75rem;">
                                                Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            @if($notifikasi->hasPages())
            <div class="card-footer bg-white border-top py-3">
                {{ $notifikasi->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
