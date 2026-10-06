<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\Kegiatan;
use App\Models\Pegawai;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $pegawai = $user->pegawai;

        $query = Disposisi::with(['kegiatan', 'dariPegawai', 'kepadaPegawai']);

        // Filter based on tab
        $tab = $request->get('tab', 'masuk');

        if ($pegawai) {
            if ($tab === 'masuk') {
                $query->where('kepada_pegawai_id', $pegawai->id);
            } else {
                $query->where('dari_pegawai_id', $pegawai->id);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $disposisi = $query->orderByDesc('created_at')->paginate(10);
        $countMasuk = $pegawai ? Disposisi::where('kepada_pegawai_id', $pegawai->id)->where('status', 'pending')->count() : 0;

        return view('disposisi.index', compact('disposisi', 'tab', 'countMasuk'));
    }

    public function create(Request $request)
    {
        $kegiatan = Kegiatan::where('status', 'aktif')->orderByDesc('tanggal')->get();
        $pegawai = Pegawai::where('status', 'aktif')->orderBy('nama')->get();
        $selectedKegiatan = $request->kegiatan_id ? Kegiatan::find($request->kegiatan_id) : null;

        return view('disposisi.create', compact('kegiatan', 'pegawai', 'selectedKegiatan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'kepada_pegawai_id' => 'required|exists:pegawai,id',
            'catatan' => 'nullable|string',
        ]);

        $user = auth()->user();
        $dariPegawai = $user->pegawai;

        if (!$dariPegawai) {
            return back()->with('error', 'Akun Anda belum terhubung dengan data pegawai.');
        }

        $disposisi = Disposisi::create([
            'kegiatan_id' => $validated['kegiatan_id'],
            'dari_pegawai_id' => $dariPegawai->id,
            'kepada_pegawai_id' => $validated['kepada_pegawai_id'],
            'catatan' => $validated['catatan'],
            'status' => 'pending',
        ]);

        // Notify target pegawai
        $kepadaPegawai = Pegawai::find($validated['kepada_pegawai_id']);
        if ($kepadaPegawai && $kepadaPegawai->user_id) {
            $kegiatan = Kegiatan::find($validated['kegiatan_id']);
            Notifikasi::create([
                'user_id' => $kepadaPegawai->user_id,
                'judul' => 'Disposisi Baru',
                'pesan' => "Anda menerima disposisi dari {$dariPegawai->nama} untuk kegiatan: {$kegiatan->judul}",
                'link' => route('disposisi.show', $disposisi->id),
            ]);
        }

        return redirect()->route('disposisi.index')
            ->with('success', 'Disposisi berhasil dikirim.');
    }

    public function show(Disposisi $disposisi)
    {
        $disposisi->load(['kegiatan.kategori', 'kegiatan.peserta', 'dariPegawai', 'kepadaPegawai']);

        // Mark as read if current user is the recipient
        $user = auth()->user();
        if ($user->pegawai && $disposisi->kepada_pegawai_id === $user->pegawai->id && !$disposisi->dibaca_pada) {
            $disposisi->update([
                'status' => 'dibaca',
                'dibaca_pada' => now(),
            ]);
        }

        return view('disposisi.show', compact('disposisi'));
    }

    public function updateStatus(Request $request, Disposisi $disposisi)
    {
        $validated = $request->validate([
            'status' => 'required|in:diproses,selesai,ditolak',
        ]);

        $disposisi->update(['status' => $validated['status']]);

        // Notify sender
        if ($disposisi->dariPegawai && $disposisi->dariPegawai->user_id) {
            $statusText = match ($validated['status']) {
                'diproses' => 'sedang diproses',
                'selesai' => 'telah diselesaikan',
                'ditolak' => 'ditolak',
            };
            Notifikasi::create([
                'user_id' => $disposisi->dariPegawai->user_id,
                'judul' => 'Update Disposisi',
                'pesan' => "Disposisi untuk kegiatan \"{$disposisi->kegiatan->judul}\" {$statusText} oleh {$disposisi->kepadaPegawai->nama}.",
                'link' => route('disposisi.show', $disposisi->id),
            ]);
        }

        return redirect()->route('disposisi.show', $disposisi)
            ->with('success', 'Status disposisi berhasil diperbarui.');
    }
}
