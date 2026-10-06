<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KategoriKegiatan;
use App\Models\Pegawai;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kegiatan::with(['kategori', 'peserta', 'creator']);

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        $kegiatan = $query->orderByDesc('tanggal')->orderBy('jam_mulai')->paginate(10);
        $kategori = KategoriKegiatan::all();

        return view('kegiatan.index', compact('kegiatan', 'kategori'));
    }

    public function kalender()
    {
        $kategori = KategoriKegiatan::all();
        return view('kegiatan.kalender', compact('kategori'));
    }

    public function calendarEvents(Request $request)
    {
        $query = Kegiatan::with('kategori')
            ->whereBetween('tanggal', [$request->start, $request->end]);

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $events = $query->get()->map(function ($kegiatan) {
            return [
                'id' => $kegiatan->id,
                'title' => $kegiatan->judul,
                'start' => $kegiatan->tanggal->format('Y-m-d') . 'T' . $kegiatan->jam_mulai,
                'end' => $kegiatan->jam_selesai
                    ? $kegiatan->tanggal->format('Y-m-d') . 'T' . $kegiatan->jam_selesai
                    : null,
                'backgroundColor' => $kegiatan->kategori?->warna ?? '#3B82F6',
                'borderColor' => $kegiatan->kategori?->warna ?? '#3B82F6',
                'extendedProps' => [
                    'tempat' => $kegiatan->tempat,
                    'status' => $kegiatan->status,
                    'prioritas' => $kegiatan->prioritas,
                    'kategori' => $kegiatan->kategori?->nama,
                ],
            ];
        });

        return response()->json($events);
    }

    public function eventsByDate(Request $request)
    {
        $date = $request->date;
        $query = Kegiatan::with(['kategori', 'peserta'])
            ->whereDate('tanggal', $date)
            ->orderBy('jam_mulai');

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $kegiatan = $query->get();

        return response()->json($kegiatan->map(function ($k) {
            return [
                'id'            => $k->id,
                'judul'         => $k->judul,
                'jam_mulai'     => $k->jam_mulai,
                'jam_selesai'   => $k->jam_selesai,
                'tempat'        => $k->tempat,
                'status'        => $k->status,
                'prioritas'     => $k->prioritas,
                'kategori'      => $k->kategori?->nama,
                'kategori_warna'=> $k->kategori?->warna,
                'jumlah_peserta'=> $k->peserta->count(),
            ];
        }));
    }

    public function create()
    {
        abort_if(auth()->user()->role === 'staff', 403, 'Anda tidak memiliki akses untuk membuat kegiatan.');
        
        $kategori = KategoriKegiatan::all();
        $pegawai = Pegawai::where('status', 'aktif')->orderBy('nama')->get();
        return view('kegiatan.create', compact('kategori', 'pegawai'));
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->role === 'staff', 403, 'Anda tidak memiliki akses untuk membuat kegiatan.');

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'nullable|exists:kategori_kegiatan,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'nullable',
            'tempat' => 'required|string|max:255',
            'status' => 'required|in:draft,aktif,selesai,batal',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'peserta' => 'nullable|array',
            'peserta.*' => 'exists:pegawai,id',
        ]);

        $kegiatan = Kegiatan::create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        if ($request->filled('peserta')) {
            $kegiatan->peserta()->attach($request->peserta);

            // Send notification to peserta
            foreach ($request->peserta as $pegawaiId) {
                $pegawai = Pegawai::find($pegawaiId);
                if ($pegawai && $pegawai->user_id) {
                    Notifikasi::create([
                        'user_id' => $pegawai->user_id,
                        'judul' => 'Kegiatan Baru',
                        'pesan' => "Anda ditambahkan sebagai peserta kegiatan: {$kegiatan->judul}",
                        'link' => route('kegiatan.show', $kegiatan->id),
                    ]);
                }
            }
        }

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load(['kategori', 'peserta', 'disposisi.dariPegawai', 'disposisi.kepadaPegawai', 'dokumentasi', 'creator']);
        return view('kegiatan.show', compact('kegiatan'));
    }

    public function edit(Kegiatan $kegiatan)
    {
        abort_if(auth()->user()->role === 'staff', 403, 'Anda tidak memiliki akses untuk mengedit kegiatan.');

        $kategori = KategoriKegiatan::all();
        $pegawai = Pegawai::where('status', 'aktif')->orderBy('nama')->get();
        $selectedPeserta = $kegiatan->peserta->pluck('id')->toArray();
        return view('kegiatan.edit', compact('kegiatan', 'kategori', 'pegawai', 'selectedPeserta'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        abort_if(auth()->user()->role === 'staff', 403, 'Anda tidak memiliki akses untuk mengedit kegiatan.');

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'nullable|exists:kategori_kegiatan,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'nullable',
            'tempat' => 'required|string|max:255',
            'status' => 'required|in:draft,aktif,selesai,batal',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'peserta' => 'nullable|array',
            'peserta.*' => 'exists:pegawai,id',
        ]);

        $kegiatan->update($validated);

        if ($request->has('peserta')) {
            $kegiatan->peserta()->sync($request->peserta ?? []);
        }

        return redirect()->route('kegiatan.show', $kegiatan)
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        abort_if(auth()->user()->role === 'staff', 403, 'Anda tidak memiliki akses untuk menghapus kegiatan.');

        $kegiatan->delete();
        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function updateKehadiran(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'kehadiran' => 'required|array',
            'kehadiran.*.pegawai_id' => 'required|exists:pegawai,id',
            'kehadiran.*.status' => 'required|in:belum,hadir,izin,alpa',
        ]);

        foreach ($validated['kehadiran'] as $data) {
            $kegiatan->peserta()->updateExistingPivot($data['pegawai_id'], [
                'status_kehadiran' => $data['status'],
            ]);
        }

        return redirect()->route('kegiatan.show', $kegiatan)
            ->with('success', 'Status kehadiran berhasil diperbarui.');
    }
}
