<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\KategoriKegiatan;
use App\Models\Kegiatan;
use App\Models\Disposisi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@simada.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Camat
        $camatUser = User::create([
            'name' => 'Drs. H. Ahmad Suryana, M.Si',
            'email' => 'camat@simada.test',
            'password' => Hash::make('password'),
            'role' => 'camat',
        ]);

        // Create UMPEG
        $umpeg = User::create([
            'name' => 'Siti Rahmawati, S.Sos',
            'email' => 'umpeg@simada.test',
            'password' => Hash::make('password'),
            'role' => 'umpeg',
        ]);

        // Create Kasubag
        $kasubag = User::create([
            'name' => 'Budi Santoso, S.AP',
            'email' => 'kasubag@simada.test',
            'password' => Hash::make('password'),
            'role' => 'kasubag',
        ]);

        // Create Staff users
        $staffUsers = [];
        $staffNames = [
            ['name' => 'Rina Marlina, A.Md', 'email' => 'rina@simada.test'],
            ['name' => 'Dedi Kurniawan', 'email' => 'dedi@simada.test'],
            ['name' => 'Eka Putri, S.Kom', 'email' => 'eka@simada.test'],
            ['name' => 'Firman Hidayat, S.E', 'email' => 'firman@simada.test'],
            ['name' => 'Gita Permatasari, S.H', 'email' => 'gita@simada.test'],
        ];

        foreach ($staffNames as $data) {
            $staffUsers[] = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]);
        }

        // Create Pegawai
        $pegawaiData = [
            ['user_id' => $camatUser->id, 'nip' => '196508151990031005', 'nama' => 'Drs. H. Ahmad Suryana, M.Si', 'jabatan' => 'Camat', 'pangkat_golongan' => 'IV/b - Pembina Tk.I', 'unit_kerja' => 'Kecamatan', 'no_hp' => '08123456001'],
            ['user_id' => $umpeg->id, 'nip' => '197203201998032004', 'nama' => 'Siti Rahmawati, S.Sos', 'jabatan' => 'Sekretaris Camat / UMPEG', 'pangkat_golongan' => 'III/d - Penata Tk.I', 'unit_kerja' => 'Sekretariat', 'no_hp' => '08123456002'],
            ['user_id' => $kasubag->id, 'nip' => '198005102005011003', 'nama' => 'Budi Santoso, S.AP', 'jabatan' => 'Kasubag Umum & Kepegawaian', 'pangkat_golongan' => 'III/c - Penata', 'unit_kerja' => 'Sub Bagian Umum', 'no_hp' => '08123456003'],
            ['user_id' => $staffUsers[0]->id, 'nip' => '199201152015042002', 'nama' => 'Rina Marlina, A.Md', 'jabatan' => 'Staf Administrasi', 'pangkat_golongan' => 'II/c - Pengatur', 'unit_kerja' => 'Sub Bagian Umum', 'no_hp' => '08123456004'],
            ['user_id' => $staffUsers[1]->id, 'nip' => '198807082010011006', 'nama' => 'Dedi Kurniawan', 'jabatan' => 'Staf Pelayanan', 'pangkat_golongan' => 'II/b - Pengatur Muda Tk.I', 'unit_kerja' => 'Seksi Pelayanan', 'no_hp' => '08123456005'],
            ['user_id' => $staffUsers[2]->id, 'nip' => '199503222019032008', 'nama' => 'Eka Putri, S.Kom', 'jabatan' => 'Staf IT & Informasi', 'pangkat_golongan' => 'III/a - Penata Muda', 'unit_kerja' => 'Sub Bagian Perencanaan', 'no_hp' => '08123456006'],
            ['user_id' => $staffUsers[3]->id, 'nip' => '199108172017041005', 'nama' => 'Firman Hidayat, S.E', 'jabatan' => 'Staf Keuangan', 'pangkat_golongan' => 'III/a - Penata Muda', 'unit_kerja' => 'Sub Bagian Keuangan', 'no_hp' => '08123456007'],
            ['user_id' => $staffUsers[4]->id, 'nip' => '199407302019032010', 'nama' => 'Gita Permatasari, S.H', 'jabatan' => 'Staf Hukum & Trantib', 'pangkat_golongan' => 'III/a - Penata Muda', 'unit_kerja' => 'Seksi Trantib', 'no_hp' => '08123456008'],
        ];

        $pegawaiModels = [];
        foreach ($pegawaiData as $data) {
            $pegawaiModels[] = Pegawai::create($data);
        }

        // Create Kategori Kegiatan
        $kategoriData = [
            ['nama' => 'Rapat', 'warna' => '#3B82F6', 'icon' => 'bi-people-fill'],
            ['nama' => 'Upacara', 'warna' => '#EF4444', 'icon' => 'bi-flag-fill'],
            ['nama' => 'Sosialisasi', 'warna' => '#10B981', 'icon' => 'bi-megaphone-fill'],
            ['nama' => 'Pelayanan', 'warna' => '#F59E0B', 'icon' => 'bi-hand-thumbs-up-fill'],
            ['nama' => 'Kunjungan', 'warna' => '#8B5CF6', 'icon' => 'bi-geo-alt-fill'],
            ['nama' => 'Pelatihan', 'warna' => '#EC4899', 'icon' => 'bi-book-fill'],
            ['nama' => 'Musyawarah', 'warna' => '#06B6D4', 'icon' => 'bi-chat-dots-fill'],
            ['nama' => 'Lainnya', 'warna' => '#6B7280', 'icon' => 'bi-three-dots'],
        ];

        foreach ($kategoriData as $data) {
            KategoriKegiatan::create($data);
        }

        // Create sample Kegiatan
        $kegiatanData = [
            [
                'created_by' => $camatUser->id,
                'kategori_id' => 1,
                'judul' => 'Rapat Koordinasi Bulanan',
                'deskripsi' => 'Rapat koordinasi bulanan seluruh pegawai kecamatan membahas program kerja dan evaluasi kinerja bulan sebelumnya.',
                'tanggal' => now()->format('Y-m-d'),
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:00',
                'tempat' => 'Aula Kecamatan',
                'status' => 'aktif',
                'prioritas' => 'tinggi',
            ],
            [
                'created_by' => $umpeg->id,
                'kategori_id' => 2,
                'judul' => 'Upacara Hari Kemerdekaan RI',
                'deskripsi' => 'Upacara peringatan Hari Kemerdekaan Republik Indonesia ke-81.',
                'tanggal' => now()->addDays(2)->format('Y-m-d'),
                'jam_mulai' => '07:00',
                'jam_selesai' => '09:00',
                'tempat' => 'Lapangan Kecamatan',
                'status' => 'aktif',
                'prioritas' => 'tinggi',
            ],
            [
                'created_by' => $umpeg->id,
                'kategori_id' => 3,
                'judul' => 'Sosialisasi Program Bantuan Sosial',
                'deskripsi' => 'Sosialisasi program bantuan sosial dari pemerintah pusat kepada masyarakat.',
                'tanggal' => now()->addDays(5)->format('Y-m-d'),
                'jam_mulai' => '09:00',
                'jam_selesai' => '12:00',
                'tempat' => 'Balai Desa Sukamaju',
                'status' => 'aktif',
                'prioritas' => 'sedang',
            ],
            [
                'created_by' => $kasubag->id,
                'kategori_id' => 6,
                'judul' => 'Pelatihan Administrasi Digital',
                'deskripsi' => 'Pelatihan penggunaan sistem administrasi digital untuk seluruh staf kecamatan.',
                'tanggal' => now()->addDays(7)->format('Y-m-d'),
                'jam_mulai' => '08:30',
                'jam_selesai' => '15:00',
                'tempat' => 'Ruang Komputer Kecamatan',
                'status' => 'aktif',
                'prioritas' => 'sedang',
            ],
            [
                'created_by' => $camatUser->id,
                'kategori_id' => 5,
                'judul' => 'Kunjungan Kerja ke Desa Mekarjaya',
                'deskripsi' => 'Kunjungan kerja camat ke Desa Mekarjaya untuk memantau proyek pembangunan infrastruktur.',
                'tanggal' => now()->subDays(2)->format('Y-m-d'),
                'jam_mulai' => '10:00',
                'jam_selesai' => '14:00',
                'tempat' => 'Desa Mekarjaya',
                'status' => 'selesai',
                'prioritas' => 'sedang',
            ],
            [
                'created_by' => $umpeg->id,
                'kategori_id' => 1,
                'judul' => 'Rapat Evaluasi Kinerja Triwulan',
                'deskripsi' => 'Rapat evaluasi kinerja pegawai per triwulan beserta penyusunan target capaian.',
                'tanggal' => now()->addDays(10)->format('Y-m-d'),
                'jam_mulai' => '09:00',
                'jam_selesai' => '11:30',
                'tempat' => 'Ruang Rapat Camat',
                'status' => 'draft',
                'prioritas' => 'tinggi',
            ],
            [
                'created_by' => $kasubag->id,
                'kategori_id' => 7,
                'judul' => 'Musrenbang Kecamatan',
                'deskripsi' => 'Musyawarah Perencanaan Pembangunan tingkat Kecamatan tahun anggaran 2027.',
                'tanggal' => now()->addDays(14)->format('Y-m-d'),
                'jam_mulai' => '08:00',
                'jam_selesai' => '16:00',
                'tempat' => 'Aula Kecamatan',
                'status' => 'aktif',
                'prioritas' => 'tinggi',
            ],
            [
                'created_by' => $umpeg->id,
                'kategori_id' => 4,
                'judul' => 'Pelayanan Terpadu Kependudukan',
                'deskripsi' => 'Pelayanan terpadu pembuatan KTP, KK, dan dokumen kependudukan lainnya.',
                'tanggal' => now()->subDays(1)->format('Y-m-d'),
                'jam_mulai' => '08:00',
                'jam_selesai' => '15:00',
                'tempat' => 'Loket Pelayanan Kecamatan',
                'status' => 'selesai',
                'prioritas' => 'sedang',
            ],
        ];

        foreach ($kegiatanData as $data) {
            $kegiatan = Kegiatan::create($data);

            // Assign random peserta
            $randomPegawai = collect($pegawaiModels)->random(rand(3, 6));
            foreach ($randomPegawai as $pegawai) {
                $kegiatan->peserta()->attach($pegawai->id, [
                    'status_kehadiran' => $kegiatan->status === 'selesai'
                        ? collect(['hadir', 'hadir', 'hadir', 'izin'])->random()
                        : 'belum',
                ]);
            }
        }

        // Create sample disposisi
        $kegiatan1 = Kegiatan::first();
        Disposisi::create([
            'kegiatan_id' => $kegiatan1->id,
            'dari_pegawai_id' => $pegawaiModels[0]->id, // Camat
            'kepada_pegawai_id' => $pegawaiModels[1]->id, // UMPEG
            'catatan' => 'Mohon disiapkan bahan rapat dan daftar hadir untuk rapat koordinasi bulanan.',
            'status' => 'diproses',
            'dibaca_pada' => now(),
        ]);

        Disposisi::create([
            'kegiatan_id' => $kegiatan1->id,
            'dari_pegawai_id' => $pegawaiModels[1]->id, // UMPEG
            'kepada_pegawai_id' => $pegawaiModels[2]->id, // Kasubag
            'catatan' => 'Tolong siapkan ruangan dan konsumsi untuk rapat.',
            'status' => 'pending',
        ]);

        $kegiatan2 = Kegiatan::find(2);
        if ($kegiatan2) {
            Disposisi::create([
                'kegiatan_id' => $kegiatan2->id,
                'dari_pegawai_id' => $pegawaiModels[0]->id,
                'kepada_pegawai_id' => $pegawaiModels[3]->id,
                'catatan' => 'Siapkan tata upacara dan susunan acara.',
                'status' => 'pending',
            ]);
        }

        echo "Seeding completed!\n";
        echo "Login accounts:\n";
        echo "Admin: admin@simada.test / password\n";
        echo "Camat: camat@simada.test / password\n";
        echo "UMPEG: umpeg@simada.test / password\n";
        echo "Kasubag: kasubag@simada.test / password\n";
        echo "Staff: rina@simada.test / password\n";
    }
}
