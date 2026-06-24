<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\JalurPendaftaran;
use App\Models\Registration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        User::firstOrCreate(
            ['email' => 'admin@ppdb.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Create Panitia
        User::firstOrCreate(
            ['email' => 'panitia@ppdb.com'],
            [
                'name' => 'Panitia PPDB',
                'password' => Hash::make('panitia123'),
                'role' => 'panitia',
            ]
        );

        // Create Jalur
        $jalurReguler = JalurPendaftaran::firstOrCreate(
            ['nama_jalur' => 'Reguler'],
            [
                'kuota' => 20,
                'deskripsi' => 'Jalur pendaftaran reguler umum.',
                'is_active' => true,
            ]
        );

        $jalurPrestasi = JalurPendaftaran::firstOrCreate(
            ['nama_jalur' => 'Prestasi'],
            [
                'kuota' => 8,
                'deskripsi' => 'Jalur pendaftaran untuk siswa berprestasi akademik maupun non-akademik.',
                'is_active' => true,
            ]
        );

        $jalurZonasi = JalurPendaftaran::firstOrCreate(
            ['nama_jalur' => 'Zonasi'],
            [
                'kuota' => 5,
                'deskripsi' => 'Jalur pendaftaran berdasarkan jarak tempat tinggal terdekat dari sekolah.',
                'is_active' => true,
            ]
        );

        // Create 5 Dummy Students
        $statuses = ['pending', 'verified', 'accepted', 'rejected', 'pending'];
        $jalurOptions = [$jalurReguler->id, $jalurPrestasi->id, $jalurZonasi->id, $jalurReguler->id, $jalurZonasi->id];

        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['email' => "siswa$i@ppdb.com"],
                [
                    'name' => "Siswa Dummy $i",
                    'password' => Hash::make('siswa123'),
                    'role' => 'siswa',
                ]
            );

            $student = Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nisn' => '001234567' . $i,
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '2010-0' . $i . '-10',
                    'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                    'asal_sekolah' => "SMP Negeri $i",
                    'nama_ayah' => "Ayah $i",
                    'nama_ibu' => "Ibu $i",
                    'no_hp' => "08123456789$i",
                    'alamat' => "Jalan Merdeka No. $i, Jakarta",
                ]
            );

            Registration::firstOrCreate(
                ['student_id' => $student->id],
                [
                    'jalur_pendaftaran_id' => $jalurOptions[$i - 1],
                    'no_pendaftaran' => 'PPDB-2026-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'status' => $statuses[$i - 1],
                    'nilai_rata_rata' => rand(70, 95) + (rand(0, 99) / 100),
                ]
            );
        }
    }
}
