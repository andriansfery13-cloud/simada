<?php

namespace App\Imports;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class PegawaiImport implements ToModel, WithHeadingRow, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    private $rowsImported = 0;
    private $rowsSkipped = 0;
    private $accountsCreated = 0;
    private $buatAkun;
    private $defaultRole;
    private $defaultPassword;

    public function __construct(bool $buatAkun = false, string $defaultRole = 'staff', string $defaultPassword = 'password')
    {
        $this->buatAkun = $buatAkun;
        $this->defaultRole = $defaultRole;
        $this->defaultPassword = $defaultPassword;
    }

    public function model(array $row)
    {
        // Normalize heading keys (handle many variations)
        $nip = $this->getString($row, ['nip', 'nik', 'no_pegawai', 'nomor_induk']);
        $nama = $this->getString($row, ['nama', 'nama_pegawai', 'nama_lengkap', 'name']);
        $jabatan = $this->getString($row, ['jabatan', 'posisi', 'position']);
        $pangkatGolongan = $this->getString($row, ['pangkat_golongan', 'pangkat', 'golongan', 'pangkatgolongan']);
        $unitKerja = $this->getString($row, ['unit_kerja', 'unit', 'bidang', 'instansi', 'unitkerja']);
        $noHp = $this->getString($row, ['no_hp', 'telepon', 'hp', 'no_telepon', 'nohp', 'phone']);
        $status = $this->getString($row, ['status']);
        $email = $this->getString($row, ['email', 'e_mail', 'mail']);
        $role = $this->getString($row, ['role', 'peran', 'hak_akses']);

        // Minimal nip dan nama harus ada
        if (!$nip || !$nama) {
            $this->rowsSkipped++;
            return null;
        }

        // Jika jabatan kosong, isi default
        if (!$jabatan) {
            $jabatan = '-';
        }

        // Skip if NIP already exists
        if (Pegawai::where('nip', $nip)->exists()) {
            $this->rowsSkipped++;
            return null;
        }

        // Normalize status (auto-lowercase)
        $status = strtolower(trim($status ?: 'aktif'));
        // Map variasi status ke aktif/nonaktif
        if (in_array($status, ['aktif', 'active', 'tetap', 'pns', 'yes', '1'])) {
            $status = 'aktif';
        } elseif (in_array($status, ['nonaktif', 'inactive', 'tidak tetap', 'non aktif', 'no', '0'])) {
            $status = 'nonaktif';
        } else {
            $status = 'aktif';
        }

        // Normalize role (auto-lowercase)
        $role = strtolower(trim($role ?: ''));

        // Create user account if enabled
        $userId = null;
        if ($this->buatAkun && $email && !User::where('email', $email)->exists()) {
            $validRole = in_array($role, ['admin', 'camat', 'umpeg', 'kasubag', 'staff']) ? $role : $this->defaultRole;

            $user = User::create([
                'name' => $nama,
                'email' => $email,
                'password' => Hash::make($this->defaultPassword),
                'role' => $validRole,
            ]);
            $userId = $user->id;
            $this->accountsCreated++;
        } elseif ($email && User::where('email', $email)->exists()) {
            // Link to existing user
            $user = User::where('email', $email)->first();
            if (!Pegawai::where('user_id', $user->id)->exists()) {
                $userId = $user->id;
            }
        }

        $this->rowsImported++;

        return new Pegawai([
            'user_id' => $userId,
            'nip' => $nip,
            'nama' => $nama,
            'jabatan' => $jabatan,
            'pangkat_golongan' => $pangkatGolongan,
            'unit_kerja' => $unitKerja,
            'no_hp' => $noHp,
            'status' => $status,
        ]);
    }

    /**
     * Ambil value dari row sebagai string, cek beberapa kemungkinan key.
     */
    private function getString(array $row, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (isset($row[$key]) && $row[$key] !== null && $row[$key] !== '') {
                $value = $row[$key];
                // Konversi numeric ke string (handle scientific notation dari Excel)
                if (is_float($value)) {
                    return number_format($value, 0, '', '');
                }
                if (is_numeric($value)) {
                    return (string) $value;
                }
                return trim((string) $value);
            }
        }
        return null;
    }

    public function getRowsImported(): int
    {
        return $this->rowsImported;
    }

    public function getRowsSkipped(): int
    {
        return $this->rowsSkipped;
    }

    public function getAccountsCreated(): int
    {
        return $this->accountsCreated;
    }
}
