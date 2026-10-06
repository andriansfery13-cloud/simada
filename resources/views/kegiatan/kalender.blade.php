@extends('layouts.app')

@section('title', 'Kalender Kegiatan')

@push('styles')
<style>
    /* ===== MOBILE CALENDAR ===== */
    .calendar-page {
        max-width: 100%;
    }

    /* Custom mini calendar */
    .mini-calendar {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 16px;
    }

    .calendar-header {
        background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
        color: white;
        padding: 18px 20px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .calendar-month-label {
        font-size: 17px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .calendar-nav {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.2s;
    }

    .calendar-nav:hover {
        background: rgba(255,255,255,0.35);
    }

    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        padding: 10px 8px 4px;
        background: #fff;
    }

    .calendar-weekday {
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 4px 0;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        padding: 4px 8px 12px;
        background: #fff;
        gap: 2px;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        color: #334155;
        position: relative;
        transition: all 0.15s;
        max-width: 42px;
        margin: 0 auto;
        width: 100%;
    }

    .calendar-day:hover:not(.empty):not(.other-month) {
        background: rgba(99, 102, 241, 0.1);
        color: #6366F1;
    }

    .calendar-day.other-month {
        color: #CBD5E1;
        cursor: default;
    }

    .calendar-day.today {
        border: 2px solid #6366F1;
        color: #6366F1;
        font-weight: 700;
    }

    .calendar-day.selected {
        background: #6366F1;
        color: white;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }

    .calendar-day.selected.today {
        border-color: transparent;
    }

    .calendar-day.has-event .event-dot {
        display: block;
    }

    .event-dot {
        display: none;
        width: 5px;
        height: 5px;
        background: #F59E0B;
        border-radius: 50%;
        position: absolute;
        bottom: 3px;
        left: 50%;
        transform: translateX(-50%);
    }

    .calendar-day.selected .event-dot {
        background: rgba(255,255,255,0.8);
    }

    .calendar-day.sunday { color: #EF4444; }
    .calendar-day.sunday.other-month { color: #FCA5A5; }
    .calendar-day.selected.sunday { color: white; }

    /* ===== AGENDA LIST ===== */
    .agenda-section {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .agenda-date-header {
        padding: 14px 18px 12px;
        border-bottom: 1px solid #F1F5F9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .agenda-date-label {
        font-size: 14px;
        font-weight: 700;
        color: #334155;
    }

    .agenda-date-label .date-badge {
        display: inline-block;
        background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 10px;
        margin-left: 8px;
    }

    .agenda-list {
        padding: 0;
    }

    .agenda-item {
        display: flex;
        align-items: stretch;
        padding: 14px 16px;
        border-bottom: 1px solid #F8FAFC;
        text-decoration: none;
        color: inherit;
        transition: background 0.15s;
        position: relative;
    }

    .agenda-item:hover {
        background: #FAFBFF;
    }

    .agenda-item:last-child {
        border-bottom: none;
    }

    .agenda-left-strip {
        width: 4px;
        border-radius: 4px;
        flex-shrink: 0;
        margin-right: 14px;
        background: #6366F1;
        align-self: stretch;
    }

    .agenda-content {
        flex: 1;
        min-width: 0;
    }

    .agenda-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .agenda-status-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .agenda-date-small {
        font-size: 11px;
        color: #94A3B8;
        font-weight: 500;
    }

    .agenda-title {
        font-size: 15px;
        font-weight: 700;
        color: #1E293B;
        margin: 0 0 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .agenda-location {
        font-size: 12px;
        color: #64748B;
        display: flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .agenda-time-col {
        text-align: right;
        flex-shrink: 0;
        margin-left: 10px;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
    }

    .agenda-time {
        font-size: 18px;
        font-weight: 800;
        color: #334155;
        line-height: 1;
    }

    .agenda-time-end {
        font-size: 11px;
        color: #94A3B8;
        font-weight: 500;
        margin-top: 2px;
    }

    /* Empty state */
    .agenda-empty {
        padding: 50px 20px;
        text-align: center;
    }

    .agenda-empty i {
        font-size: 48px;
        color: #CBD5E1;
        display: block;
        margin-bottom: 12px;
    }

    .agenda-empty p {
        color: #94A3B8;
        font-size: 14px;
        margin: 0;
    }

    .agenda-empty h6 {
        color: #64748B;
        font-weight: 600;
        margin-bottom: 6px;
    }

    /* Loading */
    .agenda-loading {
        padding: 40px;
        text-align: center;
    }

    /* Filter Category Pills */
    .category-pills {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding: 12px 16px;
        scrollbar-width: none;
        -ms-overflow-style: none;
        background: white;
        border-radius: 16px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .category-pills::-webkit-scrollbar {
        display: none;
    }

    .category-pill {
        flex-shrink: 0;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: 1.5px solid #E2E8F0;
        background: white;
        color: #64748B;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .category-pill.active {
        background: #6366F1;
        border-color: #6366F1;
        color: white;
        box-shadow: 0 3px 8px rgba(99,102,241,0.3);
    }

    /* Desktop: show FullCalendar, hide mobile cal */
    @media (min-width: 768px) {
        .mobile-calendar-wrapper { display: none; }
        .desktop-calendar-wrapper { display: block; }
        .desktop-calendar-wrapper .fc {
            background: var(--bg-card);
            padding: 20px;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }
        .desktop-calendar-wrapper .fc .fc-toolbar-title {
            font-size: 1.2rem;
            font-weight: 700;
        }
        .desktop-calendar-wrapper .fc .fc-button-primary {
            background-color: #6366F1;
            border-color: #6366F1;
        }
        .desktop-calendar-wrapper .fc .fc-daygrid-day-number {
            color: var(--text-primary);
            font-weight: 500;
            padding: 8px;
        }
        .desktop-calendar-wrapper .fc .fc-col-header-cell-cushion {
            color: var(--text-secondary);
            font-weight: 600;
            padding: 12px 0;
            text-decoration: none;
        }
        .desktop-calendar-wrapper .fc-theme-standard td,
        .desktop-calendar-wrapper .fc-theme-standard th {
            border-color: var(--border-color);
        }
        .desktop-calendar-wrapper .fc-event {
            cursor: pointer;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 0.75rem;
            border: none !important;
            margin-bottom: 2px;
        }
    }

    @media (max-width: 767px) {
        .mobile-calendar-wrapper { display: block; }
        .desktop-calendar-wrapper { display: none; }

        .page-header { 
            margin-bottom: 16px;
        }

        .content-area {
            padding: 12px !important;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Kalender Kegiatan</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-list-ul me-1"></i> <span class="d-none d-sm-inline">List View</span>
        </a>
        <a href="{{ route('kegiatan.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> <span class="d-none d-sm-inline">Buat Kegiatan</span>
        </a>
    </div>
</div>

{{-- ============================================================
     MOBILE CALENDAR (tampil hanya di handphone)
     ============================================================ --}}
<div class="mobile-calendar-wrapper">

    {{-- Category filter pills --}}
    <div class="category-pills">
        <button class="category-pill active" data-kategori="">Semua</button>
        @foreach($kategori as $kat)
            <button class="category-pill" data-kategori="{{ $kat->id }}" style="--dot-color: {{ $kat->warna }}">
                <span class="d-inline-block rounded-circle me-1" style="width:8px;height:8px;background:{{ $kat->warna }};vertical-align:middle;"></span>
                {{ $kat->nama }}
            </button>
        @endforeach
    </div>

    {{-- Mini Custom Calendar --}}
    <div class="mini-calendar">
        <div class="calendar-header">
            <button class="calendar-nav" id="prevMonth"><i class="bi bi-chevron-left"></i></button>
            <span class="calendar-month-label" id="monthLabel"></span>
            <button class="calendar-nav" id="nextMonth"><i class="bi bi-chevron-right"></i></button>
        </div>
        <div class="calendar-weekdays">
            <div class="calendar-weekday" style="color:#EF4444">Min</div>
            <div class="calendar-weekday">Sen</div>
            <div class="calendar-weekday">Sel</div>
            <div class="calendar-weekday">Rab</div>
            <div class="calendar-weekday">Kam</div>
            <div class="calendar-weekday">Jum</div>
            <div class="calendar-weekday">Sab</div>
        </div>
        <div class="calendar-days" id="calendarDays"></div>
    </div>

    {{-- Agenda List --}}
    <div class="agenda-section">
        <div class="agenda-date-header">
            <span class="agenda-date-label" id="selectedDateLabel">
                Pilih tanggal
            </span>
            <a href="#" id="btnAddEvent" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size:12px;">
                <i class="bi bi-plus"></i> Tambah
            </a>
        </div>

        <div class="agenda-list" id="agendaList">
            <div class="agenda-empty">
                <i class="bi bi-calendar3-event"></i>
                <h6>Pilih tanggal</h6>
                <p>Ketuk tanggal di kalender untuk melihat daftar kegiatan.</p>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     DESKTOP CALENDAR (tampil hanya di tablet/desktop)
     ============================================================ --}}
<div class="desktop-calendar-wrapper">
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Filter Kategori</h6>
                </div>
                <div class="card-body p-3">
                    <div class="form-check mb-2">
                        <input class="form-check-input filter-kategori-desktop" type="radio" name="kategori_filter_d" id="cat-all-d" value="" checked>
                        <label class="form-check-label" for="cat-all-d">Semua Kategori</label>
                    </div>
                    @foreach($kategori as $kat)
                    <div class="form-check mb-2">
                        <input class="form-check-input filter-kategori-desktop" type="radio" name="kategori_filter_d" id="cat-d-{{ $kat->id }}" value="{{ $kat->id }}">
                        <label class="form-check-label" for="cat-d-{{ $kat->id }}">
                            <span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:{{ $kat->warna }}"></span>
                            {{ $kat->nama }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div id="calendar-desktop"></div>
        </div>
    </div>
</div>

{{-- Desktop: Event Detail Modal --}}
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <span id="modal-kategori-badge" class="badge"></span>
                    <span id="modal-status-badge" class="badge"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">
                <h4 id="modal-title" class="fw-bold mb-3"></h4>
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="text-primary fs-4"><i class="bi bi-clock"></i></div>
                        <div>
                            <div class="fw-semibold text-secondary small">Waktu</div>
                            <div id="modal-time" class="fw-medium"></div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="text-danger fs-4"><i class="bi bi-geo-alt"></i></div>
                        <div>
                            <div class="fw-semibold text-secondary small">Tempat</div>
                            <div id="modal-tempat" class="fw-medium"></div>
                        </div>
                    </div>
                </div>
                <div class="d-grid">
                    <a href="#" id="modal-detail-link" class="btn btn-primary">Lihat Detail Lengkap</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    // SHARED: Fetch events by date
    // ============================================================
    const API_BY_DATE = '{{ route("api.events-by-date") }}';
    const API_EVENTS  = '{{ route("api.calendar-events") }}';

    // ============================================================
    // MOBILE CALENDAR
    // ============================================================
    const isMobile = () => window.innerWidth < 768;

    let currentYear  = new Date().getFullYear();
    let currentMonth = new Date().getMonth(); // 0-indexed
    let selectedDate = null;
    let currentKategoriMobile = '';
    let eventDates = {}; // { 'YYYY-MM-DD': true }

    const monthNames = ['Januari','Februari','Maret','April','Mei','Juni',
                        'Juli','Agustus','September','Oktober','November','Desember'];

    const pad2 = n => String(n).padStart(2, '0');

    function formatDateStr(y, m, d) {
        return `${y}-${pad2(m+1)}-${pad2(d)}`;
    }

    function formatDateLabel(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
        return `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
    }

    async function fetchEventDates(year, month) {
        const startStr = `${year}-${pad2(month+1)}-01`;
        const lastDay = new Date(year, month+1, 0).getDate();
        const endStr   = `${year}-${pad2(month+1)}-${pad2(lastDay)}`;

        let url = `${API_EVENTS}?start=${startStr}&end=${endStr}`;
        if (currentKategoriMobile) url += `&kategori_id=${currentKategoriMobile}`;

        const res = await fetch(url);
        const data = await res.json();
        eventDates = {};
        data.forEach(ev => {
            const d = ev.start.substring(0, 10);
            eventDates[d] = true;
        });
    }

    async function renderCalendar() {
        await fetchEventDates(currentYear, currentMonth);

        document.getElementById('monthLabel').textContent =
            `${monthNames[currentMonth]} ${currentYear}`;

        const container = document.getElementById('calendarDays');
        container.innerHTML = '';

        const firstDay = new Date(currentYear, currentMonth, 1).getDay(); // 0=Sun
        const lastDate = new Date(currentYear, currentMonth + 1, 0).getDate();
        const today = new Date();
        const todayStr = formatDateStr(today.getFullYear(), today.getMonth(), today.getDate());

        // Blank cells for first week
        for (let i = 0; i < firstDay; i++) {
            const blank = document.createElement('div');
            blank.className = 'calendar-day other-month';
            container.appendChild(blank);
        }

        for (let d = 1; d <= lastDate; d++) {
            const dateStr = formatDateStr(currentYear, currentMonth, d);
            const dayOfWeek = new Date(currentYear, currentMonth, d).getDay();

            const el = document.createElement('div');
            el.className = 'calendar-day';
            if (dayOfWeek === 0) el.classList.add('sunday');
            if (dateStr === todayStr) el.classList.add('today');
            if (dateStr === selectedDate) el.classList.add('selected');
            if (eventDates[dateStr]) el.classList.add('has-event');

            el.innerHTML = `${d}<span class="event-dot"></span>`;
            el.dataset.date = dateStr;

            el.addEventListener('click', () => selectDate(dateStr));
            container.appendChild(el);
        }
    }

    function selectDate(dateStr) {
        // Update selected state
        document.querySelectorAll('.calendar-day').forEach(el => el.classList.remove('selected'));
        const el = document.querySelector(`.calendar-day[data-date="${dateStr}"]`);
        if (el) el.classList.add('selected');

        selectedDate = dateStr;
        document.getElementById('selectedDateLabel').innerHTML =
            `${formatDateLabel(dateStr)} <span class="date-badge">—</span>`;
        document.getElementById('btnAddEvent').href =
            '{{ route("kegiatan.create") }}?tanggal=' + dateStr;

        loadAgenda(dateStr);
    }

    async function loadAgenda(dateStr) {
        const list = document.getElementById('agendaList');
        list.innerHTML = `<div class="agenda-loading"><div class="spinner-border spinner-border-sm text-primary"></div> <span class="text-muted small ms-2">Memuat...</span></div>`;

        let url = `${API_BY_DATE}?date=${dateStr}`;
        if (currentKategoriMobile) url += `&kategori_id=${currentKategoriMobile}`;

        const res = await fetch(url);
        const events = await res.json();

        // Update badge count
        const badgeEl = document.getElementById('selectedDateLabel').querySelector('.date-badge');
        if (badgeEl) badgeEl.textContent = `${events.length} Kegiatan`;

        list.innerHTML = '';

        if (events.length === 0) {
            list.innerHTML = `
                <div class="agenda-empty">
                    <i class="bi bi-cup-hot"></i>
                    <h6>Tidak ada kegiatan</h6>
                    <p>Belum ada agenda pada tanggal ini.</p>
                </div>`;
            return;
        }

        events.forEach(ev => {
            const startHH = ev.jam_mulai ? ev.jam_mulai.substring(0,5) : '--:--';
            const endHH   = ev.jam_selesai ? ev.jam_selesai.substring(0,5) : null;

            const statusMap = {
                aktif: { cls: 'bg-success text-white', label: 'Aktif' },
                selesai: { cls: 'bg-primary text-white', label: 'Selesai' },
                draft: { cls: 'bg-secondary text-white', label: 'Draft' },
                batal: { cls: 'bg-danger text-white', label: 'Batal' },
            };
            const st = statusMap[ev.status] || { cls: 'bg-secondary text-white', label: ev.status };
            const katWarna = ev.kategori_warna || '#6366F1';

            const item = document.createElement('a');
            item.href = `/kegiatan/${ev.id}`;
            item.className = 'agenda-item';
            item.innerHTML = `
                <div class="agenda-left-strip" style="background: ${katWarna};"></div>
                <div class="agenda-content">
                    <div class="agenda-meta">
                        <span class="agenda-status-badge ${st.cls}">${st.label}</span>
                        <span class="agenda-date-small">${formatDateLabel(dateStr)}</span>
                    </div>
                    <h6 class="agenda-title">${ev.judul}</h6>
                    <div class="agenda-location">
                        <i class="bi bi-geo-alt-fill" style="color:${katWarna}; font-size:11px;"></i>
                        ${ev.tempat}
                    </div>
                </div>
                <div class="agenda-time-col">
                    <span class="agenda-time">${startHH}</span>
                    <span class="agenda-time-end">s/d ${endHH ?? '-'}</span>
                </div>`;
            list.appendChild(item);
        });
    }

    // Prev/Next month
    document.getElementById('prevMonth').addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) { currentMonth = 11; currentYear--; }
        renderCalendar();
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) { currentMonth = 0; currentYear++; }
        renderCalendar();
    });

    // Category pills (mobile)
    document.querySelectorAll('.category-pill').forEach(pill => {
        pill.addEventListener('click', () => {
            document.querySelectorAll('.category-pill').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            currentKategoriMobile = pill.dataset.kategori;
            renderCalendar();
            if (selectedDate) loadAgenda(selectedDate);
        });
    });

    // Init mobile calendar
    renderCalendar();

    // Auto-select today on mobile
    const todayStr = formatDateStr(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
    setTimeout(() => selectDate(todayStr), 100);


    // ============================================================
    // DESKTOP CALENDAR (FullCalendar)
    // ============================================================
    let currentKategoriDesktop = '';

    const desktopEl = document.getElementById('calendar-desktop');
    if (desktopEl) {
        const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));

        const calendar = new FullCalendar.Calendar(desktopEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                list: 'Agenda'
            },
            events: function (info, successCallback, failureCallback) {
                let url = `${API_EVENTS}?start=${info.startStr}&end=${info.endStr}`;
                if (currentKategoriDesktop) url += `&kategori_id=${currentKategoriDesktop}`;
                fetch(url).then(r => r.json()).then(data => successCallback(data)).catch(failureCallback);
            },
            eventClick: function (info) {
                info.jsEvent.preventDefault();
                const event = info.event;
                const props = event.extendedProps;

                document.getElementById('modal-title').textContent = event.title;
                const startObj = event.start;
                const endObj   = event.end;
                const fmt = d => `${pad2(d.getHours())}:${pad2(d.getMinutes())}`;
                let timeStr = startObj.toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'}) + ', ' + fmt(startObj);
                if (endObj) timeStr += ' - ' + fmt(endObj);
                document.getElementById('modal-time').textContent = timeStr;
                document.getElementById('modal-tempat').textContent = props.tempat;

                const katBadge = document.getElementById('modal-kategori-badge');
                katBadge.textContent = props.kategori || 'Umum';
                katBadge.style.backgroundColor = event.backgroundColor;
                katBadge.style.color = '#fff';

                const stBadge = document.getElementById('modal-status-badge');
                stBadge.textContent = (props.status || '').toUpperCase();
                const stMap = {aktif:'bg-success', selesai:'bg-primary', draft:'bg-secondary', batal:'bg-danger'};
                stBadge.className = 'badge ' + (stMap[props.status] || 'bg-secondary');

                document.getElementById('modal-detail-link').href = '/kegiatan/' + event.id;
                eventModal.show();
            }
        });

        calendar.render();

        document.querySelectorAll('.filter-kategori-desktop').forEach(radio => {
            radio.addEventListener('change', e => {
                currentKategoriDesktop = e.target.value;
                calendar.refetchEvents();
            });
        });
    }
});
</script>
@endpush
