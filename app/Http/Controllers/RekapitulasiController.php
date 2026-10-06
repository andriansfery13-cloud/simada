<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapitulasiController extends Controller
{
    public function index(Request $request)
    {
        $bulanSekarang = $request->get('bulan', now()->format('Y-m'));
        [$tahun, $bulan] = explode('-', $bulanSekarang);

        $search = $request->get('search');

        $query = Pegawai::where('status', 'aktif')
            ->when($search, function ($q) use ($search) {
                $q->where(function($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%")
                          ->orWhere('jabatan', 'like', "%{$search}%");
                });
            })
            ->withCount(['kegiatan as total_kegiatan' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun);
            }])
            ->withCount(['kegiatan as hadir' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun)
                  ->wherePivot('status_kehadiran', 'hadir');
            }])
            ->withCount(['kegiatan as izin' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun)
                  ->wherePivot('status_kehadiran', 'izin');
            }])
            ->withCount(['kegiatan as alpa' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun)
                  ->wherePivot('status_kehadiran', 'alpa');
            }])
            ->orderBy('nama');

        $rekapitulasi = $query->paginate(10);
        
        // Calculate belum_absen dynamically for each model in collection
        $rekapitulasi->getCollection()->transform(function ($item) {
            $item->belum_absen = $item->total_kegiatan - ($item->hadir + $item->izin + $item->alpa);
            return $item;
        });

        // Chart data (if needed by view)
        $kategoriChart = Kegiatan::select('kategori_kegiatan.nama', 'kategori_kegiatan.warna', DB::raw('COUNT(*) as total'))
            ->join('kategori_kegiatan', 'kegiatan.kategori_id', '=', 'kategori_kegiatan.id')
            ->whereMonth('kegiatan.tanggal', $bulan)
            ->whereYear('kegiatan.tanggal', $tahun)
            ->groupBy('kategori_kegiatan.nama', 'kategori_kegiatan.warna')
            ->get();

        return view('rekapitulasi.index', compact('rekapitulasi', 'bulanSekarang', 'kategoriChart'));
    }

    public function detail(Request $request, Pegawai $pegawai)
    {
        $bulanSekarang = $request->get('bulan', now()->format('Y-m'));
        [$tahun, $bulan] = explode('-', $bulanSekarang);

        $kegiatan = $pegawai->kegiatan()
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->with('kategori')
            ->orderBy('tanggal')
            ->get();

        return view('rekapitulasi.detail', compact('pegawai', 'kegiatan', 'bulanSekarang'));
    }
}
