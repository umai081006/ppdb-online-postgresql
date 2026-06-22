<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $student = $user->student;
        $registration = null;

        if ($student) {
            $registration = $student->registrations()->with(['documents', 'jalurPendaftaran'])->latest()->first();
        }

        return view('siswa.dashboard', compact('student', 'registration'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->student && $user->student->registrations()->count() > 0) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda sudah melakukan pendaftaran.');
        }

        $jalurOptions = \App\Models\JalurPendaftaran::where('is_active', true)->get();
        return view('siswa.pendaftaran', compact('jalurOptions'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->student && $user->student->registrations()->count() > 0) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda sudah melakukan pendaftaran.');
        }

        $request->validate([
            'nik' => 'required|string|max:16',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'alamat' => 'required|string',
            'kecamatan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_orang_tua' => 'required|string|max:255',
            'asal_sekolah' => 'required|string|max:255',
            'nilai_rata_rata' => 'required|numeric|min:0|max:100',
            'jalur_pendaftaran_id' => 'required|exists:jalur_pendaftarans,id',
        ]);

        $user->update(['name' => $request->nama_lengkap]);

        $student = \App\Models\Student::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'alamat' => $request->alamat,
                'kecamatan' => $request->kecamatan,
                'no_hp' => $request->no_hp,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'pekerjaan_orang_tua' => $request->pekerjaan_orang_tua,
                'asal_sekolah' => $request->asal_sekolah,
            ]
        );

        $lastRegistration = \App\Models\Registration::orderBy('id', 'desc')->first();
        $nextId = $lastRegistration ? $lastRegistration->id + 1 : 1;
        $no_pendaftaran = 'PPDB-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        \App\Models\Registration::create([
            'student_id' => $student->id,
            'jalur_pendaftaran_id' => $request->jalur_pendaftaran_id,
            'no_pendaftaran' => $no_pendaftaran,
            'status' => 'pending',
            'nilai_rata_rata' => $request->nilai_rata_rata,
        ]);

        return redirect()->route('siswa.dokumen.create')->with('success', 'Pendaftaran berhasil! Silakan upload dokumen.');
    }

    public function uploadDokumenForm()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->registrations()->count() === 0) {
            return redirect()->route('siswa.pendaftaran.create')->with('error', 'Silakan isi formulir pendaftaran terlebih dahulu.');
        }

        $registration = $student->registrations()->with('documents')->latest()->first();

        $tipeDokumen = [
            'foto_3x4'          => 'Foto 3x4',
            'kartu_keluarga'     => 'Kartu Keluarga (KK)',
            'ijazah'             => 'Ijazah',
            'rapor'              => 'Rapor',
            'piagam_prestasi'    => 'Piagam Prestasi',
        ];

        return view('siswa.upload-dokumen', compact('registration', 'tipeDokumen'));
    }

    public function uploadDokumen(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        $registration = $student->registrations()->latest()->first();

        if (!$registration) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda belum memiliki pendaftaran.');
        }

        $request->validate([
            'tipe_dokumen' => 'required|string|in:foto_3x4,kartu_keluarga,ijazah,rapor,piagam_prestasi',
        ]);

        $rules = [];
        if ($request->tipe_dokumen == 'foto_3x4') {
            $rules['file'] = 'required|file|mimes:jpg,jpeg,png|max:2048';
        } else {
            $rules['file'] = 'required|file|mimes:jpg,jpeg,pdf|max:5120';
        }

        $request->validate($rules);

        // Cek apakah tipe dokumen ini sudah pernah di-upload
        $existing = Document::where('registration_id', $registration->id)
            ->where('tipe_dokumen', $request->tipe_dokumen)
            ->first();

        if ($existing) {
            // Hapus file lama di Cloudinary
            cloudinary()->destroy($existing->cloudinary_public_id);
            $existing->delete();
        }

        // Upload ke Cloudinary menggunakan package resmi
        $uploadedFileUrl = cloudinary()->upload($request->file('file')->getRealPath(), [
            'folder' => 'ppdb-documents/' . $registration->no_pendaftaran,
        ]);

        Document::create([
            'registration_id'     => $registration->id,
            'tipe_dokumen'        => $request->tipe_dokumen,
            'cloudinary_url'      => $uploadedFileUrl->getSecurePath(),
            'cloudinary_public_id'=> $uploadedFileUrl->getPublicId(),
        ]);

        return redirect()->route('siswa.dokumen.create')->with('success', 'Dokumen berhasil di-upload!');
    }
}
