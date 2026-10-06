<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Pegawai;
use App\Models\Disposisi;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPegawai = Pegawai::where('status', 'aktif')->count();
        $totalKegiatan = Kegiatan::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        $kegiatanHariIni = Kegiatan::whereDate('tanggal', today())
            ->where('status', 'aktif')
            ->with(['kategori', 'peserta'])
            ->orderBy('jam_mulai')
            ->get();
        $disposisiPending = Disposisi::where('status', 'pending')->count();

        // Kegiatan mendatang 7 hari
        $kegiatanMendatang = Kegiatan::whereBetween('tanggal', [today(), today()->addDays(7)])
            ->where('status', 'aktif')
            ->with('kategori')
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->limit(5)
            ->get();

        // Statistik per bulan (6 bulan terakhir)
        $chartData = Kegiatan::select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw('COUNT(*) as total')
            )
            ->where('tanggal', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        $bulanLabels = [];
        $bulanData = [];
        $namaBulan = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $bulanLabels[] = $namaBulan[(int)$date->format('m')] . ' ' . $date->format('Y');
            $found = $chartData->first(function ($item) use ($date) {
                return (int)$item->bulan === (int)$date->format('m') && $item->tahun === $date->format('Y');
            });
            $bulanData[] = $found ? $found->total : 0;
        }

        // Pegawai paling aktif
        $pegawaiAktif = Pegawai::withCount(['kegiatan' => function ($query) {
                $query->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year);
            }])
            ->orderByDesc('kegiatan_count')
            ->limit(5)
            ->get();

        // Notifikasi terbaru
        $notifikasi = auth()->user()->notifikasi()
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalPegawai',
            'totalKegiatan',
            'kegiatanHariIni',
            'disposisiPending',
            'kegiatanMendatang',
            'bulanLabels',
            'bulanData',
            'pegawaiAktif',
            'notifikasi'
        ));
    }
}
