@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- ================================================================
     MOBILE DASHBOARD (< 768px)
     ================================================================ --}}
<div class="d-block d-md-none">
    {{-- Pull-up wrapper --}}
    <div class="mobile-content-pull">

        {{-- ===== QUICK MENU GRID ===== --}}
        <div class="quick-menu-card">
            <div class="quick-menu-grid">
                <a href="{{ route('kegiatan.kalender') }}" class="quick-menu-item">
                    <div class="quick-menu-icon" style="background: linear-gradient(135deg,#6366F1,#818CF8);">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <span class="quick-menu-label">Kalender</span>
                </a>

                <a href="{{ route('kegiatan.index') }}" class="quick-menu-item">
                    <div class="quick-menu-icon" style="background: linear-gradient(135deg,#0EA5E9,#38BDF8);">
                        <i class="bi bi-clipboard2-check"></i>
                    </div>
                    <span class="quick-menu-label">Kegiatan</span>
                </a>

                <a href="{{ route('pegawai.index') }}" class="quick-menu-item">
                    <div class="quick-menu-icon" style="background: linear-gradient(135deg,#10B981,#34D399);">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <span class="quick-menu-label">Pegawai</span>
                </a>

                <a href="{{ route('disposisi.index') }}" class="quick-menu-item">
                    <div class="quick-menu-icon" style="background: linear-gradient(135deg,#F59E0B,#FBBF24);">
                        <i class="bi bi-envelope-paper-fill"></i>
                        @if(isset($disposisiPending) && $disposisiPending > 0)
                            <span class="badge-count">{{ $disposisiPending }}</span>
                        @endif
                    </div>
                    <span class="quick-menu-label">Disposisi</span>
                </a>

                <a href="{{ route('rekapitulasi.index') }}" class="quick-menu-item">
                    <div class="quick-menu-icon" style="background: linear-gradient(135deg,#8B5CF6,#A78BFA);">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                    <span class="quick-menu-label">Rekap</span>
                </a>

                <a href="{{ route('laporan.index') }}" class="quick-menu-item">
                    <div class="quick-menu-icon" style="background: linear-gradient(135deg,#EF4444,#F87171);">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                    </div>
                    <span class="quick-menu-label">Laporan</span>
                </a>

                <a href="{{ route('notifikasi.index') }}" class="quick-menu-item">
                    <div class="quick-menu-icon" style="background: linear-gradient(135deg,#EC4899,#F472B6);">
                        <i class="bi bi-bell-fill"></i>
                        @php $unreadMobile = auth()->check() ? \App\Models\Notifikasi::where('user_id', auth()->id())->where('dibaca', false)->count() : 0; @endphp
                        @if($unreadMobile > 0)
                            <span class="badge-count">{{ $unreadMobile }}</span>
                        @endif
                    </div>
                    <span class="quick-menu-label">Notifikasi</span>
                </a>

                <a href="{{ route('kegiatan.create') }}" class="quick-menu-item">
                    <div class="quick-menu-icon" style="background: linear-gradient(135deg,#0F766E,#14B8A6);">
                        <i class="bi bi-plus-square-fill"></i>
                    </div>
                    <span class="quick-menu-label">Buat Baru</span>
                </a>
            </div>
        </div>

        {{-- ===== MOBILE STAT CARDS ===== --}}
        <div class="section-header">
            <span class="section-title">Ringkasan Bulan Ini</span>
        </div>

        <div class="mobile-stats-scroll">
            <div class="mobile-stat-card" style="background: linear-gradient(135deg,#6366F1,#8B5CF6);">
                <i class="bi bi-calendar-event s-bg"></i>
                <div class="s-icon"><i class="bi bi-calendar-event"></i></div>
                <div class="s-value">{{ $totalKegiatan }}</div>
                <div class="s-label">Kegiatan Bulan Ini</div>
            </div>

            <div class="mobile-stat-card" style="background: linear-gradient(135deg,#0EA5E9,#38BDF8);">
                <i class="bi bi-people s-bg"></i>
                <div class="s-icon"><i class="bi bi-person-badge"></i></div>
                <div class="s-value">{{ $totalPegawai }}</div>
                <div class="s-label">Total Pegawai</div>
            </div>

            <div class="mobile-stat-card" style="background: linear-gradient(135deg,#10B981,#34D399);">
                <i class="bi bi-check-circle s-bg"></i>
                <div class="s-icon"><i class="bi bi-calendar-check"></i></div>
                <div class="s-value">{{ $kegiatanHariIni->count() }}</div>
                <div class="s-label">Agenda Hari Ini</div>
            </div>

            <div class="mobile-stat-card" style="background: linear-gradient(135deg,#F59E0B,#FBBF24);">
                <i class="bi bi-envelope-paper s-bg"></i>
                <div class="s-icon"><i class="bi bi-envelope-exclamation"></i></div>
                <div class="s-value">{{ $disposisiPending }}</div>
                <div class="s-label">Disposisi Pending</div>
            </div>
        </div>

        {{-- ===== AGENDA HARI INI ===== --}}
        <div class="section-header mt-3">
            <span class="section-title">📅 Agenda Hari Ini</span>
            <a href="{{ route('kegiatan.kalender') }}" class="section-link">Lihat Semua</a>
        </div>

        @if($kegiatanHariIni->isEmpty())
            <div class="mobile-empty-agenda">
                <i class="bi bi-cup-hot"></i>
                <p>Tidak ada agenda hari ini.<br>Saatnya bersantai! ☕</p>
            </div>
        @else
            @foreach($kegiatanHariIni as $kegiatan)
            <a href="{{ route('kegiatan.show', $kegiatan) }}" class="mobile-agenda-item">
                <div class="mobile-agenda-strip" style="background: {{ $kegiatan->kategori->warna ?? '#6366F1' }};"></div>
                <div class="mobile-agenda-content">
                    <div class="mobile-agenda-time">
                        <i class="bi bi-clock me-1"></i>
                        {{ \Carbon\Carbon::parse($kegiatan->jam_mulai)->format('H:i') }}
                        {{ $kegiatan->jam_selesai ? '- '.\Carbon\Carbon::parse($kegiatan->jam_selesai)->format('H:i') : 's/d selesai' }}
                    </div>
                    <div class="mobile-agenda-title">{{ $kegiatan->judul }}</div>
                    <div class="mobile-agenda-loc">
                        <i class="bi bi-geo-alt-fill" style="font-size:10px;color:{{ $kegiatan->kategori->warna ?? '#6366F1' }};"></i>
                        {{ $kegiatan->tempat }}
                    </div>
                </div>
                <div>
                    @php
                        $prioritasBg = match($kegiatan->prioritas) {
                            'tinggi' => 'bg-danger text-white',
                            'sedang' => 'bg-warning text-dark',
                            default  => 'bg-secondary text-white',
                        };
                    @endphp
                    <span class="mobile-agenda-badge {{ $prioritasBg }}">{{ ucfirst($kegiatan->prioritas) }}</span>
                </div>
            </a>
            @endforeach
        @endif

        {{-- ===== NOTIFIKASI TERBARU ===== --}}
        @if($notifikasi->isNotEmpty())
        <div class="section-header mt-3">
            <span class="section-title">🔔 Pemberitahuan</span>
            <a href="{{ route('notifikasi.index') }}" class="section-link">Semua</a>
        </div>

        @foreach($notifikasi->take(3) as $notif)
        <div class="mobile-agenda-item" style="border-left: 3px solid {{ !$notif->dibaca ? '#6366F1' : '#E2E8F0' }}; padding-left: 12px;">
            <div class="mobile-agenda-content">
                <div class="mobile-agenda-time">{{ $notif->created_at->diffForHumans() }}</div>
                <div class="mobile-agenda-title" style="font-size: 13px;">
                    @if(!$notif->dibaca)<span style="display:inline-block;width:6px;height:6px;background:#6366F1;border-radius:50%;vertical-align:middle;margin-right:5px;"></span>@endif
                    {{ $notif->judul }}
                </div>
                <div class="mobile-agenda-loc">{{ Str::limit($notif->pesan, 55) }}</div>
            </div>
        </div>
        @endforeach
        @endif

    </div>
    {{-- /mobile content pull --}}
</div>
{{-- /mobile dashboard --}}


{{-- ================================================================
     DESKTOP DASHBOARD (>= 768px)
     ================================================================ --}}
<div class="d-none d-md-block">
    <div class="page-header">
        <div>
            <h1>Dashboard Utama</h1>
            <p class="text-secondary mb-0">Selamat datang kembali, {{ auth()->user()->name }}!</p>
        </div>
        <div>
            <a href="{{ route('kegiatan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Buat Kegiatan
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card stat-card gradient-primary h-100">
                <div class="card-body">
                    <i class="bi bi-calendar2-check stat-bg-pattern"></i>
                    <div class="stat-icon"><i class="bi bi-calendar-event"></i></div>
                    <div class="stat-value">{{ $totalKegiatan }}</div>
                    <div class="stat-label">Kegiatan Bulan Ini</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card stat-card gradient-secondary h-100">
                <div class="card-body">
                    <i class="bi bi-people stat-bg-pattern"></i>
                    <div class="stat-icon"><i class="bi bi-person-badge"></i></div>
                    <div class="stat-value">{{ $totalPegawai }}</div>
                    <div class="stat-label">Total Pegawai Aktif</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card stat-card gradient-success h-100">
                <div class="card-body">
                    <i class="bi bi-journal-check stat-bg-pattern"></i>
                    <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
                    <div class="stat-value">{{ $kegiatanHariIni->count() }}</div>
                    <div class="stat-label">Kegiatan Hari Ini</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card stat-card gradient-warning h-100">
                <div class="card-body">
                    <i class="bi bi-envelope-paper stat-bg-pattern"></i>
                    <div class="stat-icon"><i class="bi bi-envelope-exclamation"></i></div>
                    <div class="stat-value">{{ $disposisiPending }}</div>
                    <div class="stat-label">Disposisi Pending</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart + Agenda --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Statistik Kegiatan (6 Bulan Terakhir)</h5>
                </div>
                <div class="card-body">
                    <canvas id="kegiatanChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Agenda Hari Ini</h5>
                    <a href="{{ route('kegiatan.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @if($kegiatanHariIni->isEmpty())
                        <div class="empty-state py-4">
                            <i class="bi bi-cup-hot" style="font-size: 40px;"></i>
                            <h6 class="mt-3">Tidak ada agenda hari ini</h6>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($kegiatanHariIni as $kegiatan)
                            <a href="{{ route('kegiatan.show', $kegiatan) }}" class="list-group-item list-group-item-action p-3 border-bottom border-light">
                                <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold">{{ $kegiatan->judul }}</h6>
                                    <span class="badge bg-{{ $kegiatan->prioritas_badge }}">{{ ucfirst($kegiatan->prioritas) }}</span>
                                </div>
                                <div class="text-muted small mb-2 d-flex gap-3">
                                    <span><i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($kegiatan->jam_mulai)->format('H:i') }} - {{ $kegiatan->jam_selesai ? \Carbon\Carbon::parse($kegiatan->jam_selesai)->format('H:i') : 'Selesai' }}</span>
                                    <span><i class="bi bi-geo-alt me-1"></i> {{ $kegiatan->tempat }}</span>
                                </div>
                                <span class="badge" style="background-color: {{ $kegiatan->kategori->warna ?? '#6c757d' }}">
                                    {{ $kegiatan->kategori->nama ?? 'Umum' }}
                                </span>
                            </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Pegawai Aktif + Notifikasi --}}
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Pegawai Paling Aktif (Bulan Ini)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Pegawai</th>
                                    <th>Jabatan</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pegawaiAktif as $peg)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="user-avatar" style="width: 36px; height: 36px;">{{ $peg->inisials }}</div>
                                            <div class="fw-bold" style="font-size: 13px;">{{ $peg->nama }}</div>
                                        </div>
                                    </td>
                                    <td class="small">{{ $peg->jabatan }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill px-3 py-2">{{ $peg->kegiatan_count }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-3 text-muted">Belum ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pemberitahuan Terbaru</h5>
                    <a href="{{ route('notifikasi.index') }}" class="btn btn-sm btn-outline-secondary">Semua</a>
                </div>
                <div class="card-body p-0">
                    @if($notifikasi->isEmpty())
                        <div class="empty-state py-4">
                            <i class="bi bi-bell-slash" style="font-size: 32px;"></i>
                            <p class="mt-2 mb-0">Belum ada pemberitahuan</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($notifikasi as $notif)
                            <div class="list-group-item p-3 {{ !$notif->dibaca ? 'bg-light' : '' }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 {{ !$notif->dibaca ? 'fw-bold' : '' }} text-primary">
                                        {{ $notif->judul }}
                                        @if(!$notif->dibaca)<span class="badge bg-danger ms-1" style="font-size: 8px;">BARU</span>@endif
                                    </h6>
                                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 small">{{ $notif->pesan }}</p>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{{-- /desktop dashboard --}}

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('kegiatanChart');
    if (!ctx) return;
    
    const isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const gridColor = isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)';
    const textColor = isDarkMode ? '#94A3B8' : '#64748B';

    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(99,102,241,0.5)');
    gradient.addColorStop(1, 'rgba(99,102,241,0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($bulanLabels) !!},
            datasets: [{
                label: 'Total Kegiatan',
                data: {!! json_encode($bulanData) !!},
                borderColor: '#6366F1',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#FFFFFF',
                pointBorderColor: '#6366F1',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDarkMode ? '#1E293B' : '#FFFFFF',
                    titleColor: isDarkMode ? '#F1F5F9' : '#1E293B',
                    bodyColor: isDarkMode ? '#94A3B8' : '#64748B',
                    borderColor: isDarkMode ? '#334155' : '#E2E8F0',
                    borderWidth: 1, padding: 12, displayColors: false,
                    callbacks: { label: ctx => ctx.parsed.y + ' Kegiatan' }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor, drawBorder: false },
                    ticks: { color: textColor, stepSize: 1, padding: 10 }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: textColor, padding: 10 }
                }
            },
            interaction: { intersect: false, mode: 'index' }
        }
    });
});
</script>
@endpush
