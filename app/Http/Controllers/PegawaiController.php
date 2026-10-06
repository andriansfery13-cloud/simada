<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use App\Models\ActivityLog;
use App\Imports\PegawaiImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('unit_kerja')) {
            $query->where('unit_kerja', $request->unit_kerja);
        }

        $pegawai = $query->orderBy('nama')->paginate(10);
        $unitKerja = Pegawai::distinct()->pluck('unit_kerja')->filter();

        return view('pegawai.index', compact('pegawai', 'unitKerja'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:20|unique:pegawai,nip',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'pangkat_golongan' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:aktif,nonaktif',
            'email' => 'nullable|email|unique:users,email',
            'role' => 'nullable|in:admin,camat,umpeg,kasubag,staff',
        ]);

        // Create user account if email provided
        $userId = null;
        if ($request->filled('email')) {
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make('password'),
                'role' => $validated['role'] ?? 'staff',
            ]);
            $userId = $user->id;
        }

        $data = collect($validated)->except(['email', 'role', 'foto'])->toArray();
        $data['user_id'] = $userId;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pegawai/foto', 'public');
        }

        Pegawai::create($data);

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function show(Pegawai $pegawai)
    {
        $pegawai->load(['kegiatan' => function ($query) {
            $query->orderByDesc('tanggal')->limit(20);
        }, 'kegiatan.kategori']);

        $totalKegiatan = $pegawai->kegiatan()->count();
        $kegiatanBulanIni = $pegawai->kegiatan()
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        return view('pegawai.show', compact('pegawai', 'totalKegiatan', 'kegiatanBulanIni'));
    }

    public function edit(Pegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:20|unique:pegawai,nip,' . $pegawai->id,
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'pangkat_golongan' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data = collect($validated)->except(['foto'])->toArray();

        if ($request->hasFile('foto')) {
            if ($pegawai->foto) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            $data['foto'] = $request->file('foto')->store('pegawai/foto', 'public');
        }

        $pegawai->update($data);

        if ($pegawai->user) {
            $pegawai->user->update(['name' => $validated['nama']]);
        }

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        if ($pegawai->foto) {
            Storage::disk('public')->delete($pegawai->foto);
        }

        $pegawai->delete();

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }

    /**
     * Download template Excel untuk import pegawai.
     */
    public function downloadTemplate()
    {
        $headers = ['nip', 'nama', 'jabatan', 'pangkat_golongan', 'unit_kerja', 'no_hp', 'status', 'email', 'role'];
        $example = ['198501012010011001', 'Ahmad Fauzi', 'Kepala Seksi Pelayanan', 'III/c - Penata', 'Seksi Pelayanan', '081234567890', 'aktif', 'ahmad.fauzi@example.com', 'staff'];

        $callback = function () use ($headers, $example) {
            $file = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headers);
            fputcsv($file, $example);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_pegawai.csv"',
        ]);
    }

    /**
     * Import data pegawai dari file Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
            'buat_akun' => 'nullable|boolean',
            'default_role' => 'nullable|in:admin,camat,umpeg,kasubag,staff',
            'default_password' => 'nullable|string|min:6',
        ]);

        $buatAkun = $request->boolean('buat_akun', false);
        $defaultRole = $request->input('default_role', 'staff');
        $defaultPassword = $request->input('default_password', 'password');

        try {
            $import = new PegawaiImport($buatAkun, $defaultRole, $defaultPassword);
            Excel::import($import, $request->file('file'));

            $imported = $import->getRowsImported();
            $skipped = $import->getRowsSkipped();
            $accounts = $import->getAccountsCreated();
            $failures = $import->failures();

            $message = "Import selesai: {$imported} data pegawai berhasil diimport.";
            if ($accounts > 0) {
                $message .= " {$accounts} akun pengguna berhasil dibuat.";
            }
            if ($skipped > 0) {
                $message .= " {$skipped} baris dilewati (duplikat/tidak lengkap).";
            }
            if ($failures->count() > 0) {
                $message .= " {$failures->count()} baris gagal validasi.";
            }

            return redirect()->route('pegawai.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('pegawai.index')
                ->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
