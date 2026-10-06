<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'tipe' => 'required|in:kegiatan,pegawai,rekapitulasi',
            'tanggal_dari' => 'required|date',
            'tanggal_sampai' => 'required|date|after_or_equal:tanggal_dari',
            'format' => 'required|in:pdf,excel',
        ]);

        $dari = $validated['tanggal_dari'];
        $sampai = $validated['tanggal_sampai'];

        if ($validated['tipe'] === 'kegiatan') {
            $data = Kegiatan::with(['kategori', 'peserta'])
                ->whereBetween('tanggal', [$dari, $sampai])
                ->orderBy('tanggal')
                ->get();

            if ($validated['format'] === 'pdf') {
                $pdf = Pdf::loadView('laporan.pdf.kegiatan', [
                    'data' => $data,
                    'dari' => $dari,
                    'sampai' => $sampai,
                ])->setPaper('a4', 'landscape');

                return $pdf->download('Laporan_Kegiatan_' . $dari . '_' . $sampai . '.pdf');
            }
        }

        if ($validated['tipe'] === 'rekapitulasi') {
            $data = Pegawai::where('status', 'aktif')
                ->withCount(['kegiatan as total_kegiatan' => function ($q) use ($dari, $sampai) {
                    $q->whereBetween('tanggal', [$dari, $sampai]);
                }])
                ->withCount(['kegiatan as total_hadir' => function ($q) use ($dari, $sampai) {
                    $q->whereBetween('tanggal', [$dari, $sampai])
                        ->wherePivot('status_kehadiran', 'hadir');
                }])
                ->orderBy('nama')
                ->get();

            if ($validated['format'] === 'pdf') {
                $pdf = Pdf::loadView('laporan.pdf.rekapitulasi', [
                    'data' => $data,
                    'dari' => $dari,
                    'sampai' => $sampai,
                ])->setPaper('a4', 'portrait');

                return $pdf->download('Laporan_Rekapitulasi_' . $dari . '_' . $sampai . '.pdf');
            }
        }

        return back()->with('info', 'Laporan sedang diproses.');
    }
}
