<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\JalurPendaftaran;
use Illuminate\Support\Facades\Cache;
use App\Exports\RegistrationsExport;
use Maatwebsite\Excel\Facades\Excel;

class SelectionController extends Controller
{
    public function index()
    {
        $previewData = Cache::get('selection_preview_data', null);

        // Check if results have already been published (any registration with final status)
        $isPublished = Registration::whereIn('status', ['diterima', 'tidak_diterima'])->exists();

        // Build published results summary grouped by jalur
        $publishedResults = null;
        if ($isPublished) {
            $jalurs = JalurPendaftaran::where('is_active', true)->get();
            $publishedResults = [];
            foreach ($jalurs as $jalur) {
                $accepted = Registration::with(['student.user', 'jalurPendaftaran'])
                    ->where('jalur_pendaftaran_id', $jalur->id)
                    ->where('status', 'diterima')
                    ->orderBy('nilai_rata_rata', 'desc')
                    ->get();

                $rejected = Registration::with(['student.user', 'jalurPendaftaran'])
                    ->where('jalur_pendaftaran_id', $jalur->id)
                    ->where('status', 'tidak_diterima')
                    ->orderBy('nilai_rata_rata', 'desc')
                    ->get();

                if ($accepted->count() > 0 || $rejected->count() > 0) {
                    $publishedResults[$jalur->id] = [
                        'jalur' => $jalur,
                        'accepted' => $accepted,
                        'rejected' => $rejected,
                    ];
                }
            }
        }

        $totalDiterima = Registration::where('status', 'diterima')->count();
        $totalTidakDiterima = Registration::where('status', 'tidak_diterima')->count();

        return view('admin.selection.index', compact(
            'previewData', 'isPublished', 'publishedResults',
            'totalDiterima', 'totalTidakDiterima'
        ));
    }

    public function run()
    {
        // Prevent re-running if already published
        $isPublished = Registration::whereIn('status', ['diterima', 'tidak_diterima'])->exists();
        if ($isPublished) {
            return redirect()->route('admin.selection.index')
                ->with('error', 'Hasil seleksi sudah dipublikasikan. Reset terlebih dahulu untuk menjalankan seleksi ulang.');
        }

        $jalurs = JalurPendaftaran::where('is_active', true)->get();
        
        $previewData = [];
        $schoolKecamatan = env('SEKOLAH_KECAMATAN', 'Ciputat'); // Default fallback

        foreach ($jalurs as $jalur) {
            $query = Registration::with(['student.user'])
                ->where('jalur_pendaftaran_id', $jalur->id)
                ->where('status', 'verified') // Only process verified registrations
                ->orderBy('nilai_rata_rata', 'desc');

            $namaJalur = strtolower($jalur->nama_jalur);

            if (str_contains($namaJalur, 'prestasi')) {
                // Must have piagam
                $query->whereHas('documents', function($q) {
                    $q->where('tipe_dokumen', 'piagam_prestasi')
                      ->where('status', 'approved');
                });
            } elseif (str_contains($namaJalur, 'zonasi')) {
                // Kecamatan must match school
                $query->whereHas('student', function($q) use ($schoolKecamatan) {
                    $q->where('kecamatan', 'like', "%{$schoolKecamatan}%");
                });
            }

            // Reguler has no extra constraints

            $accepted = $query->take($jalur->kuota)->get();
            $acceptedIds = $accepted->pluck('id')->toArray();

            // The rest are rejected in this preview
            $rejected = Registration::with(['student.user'])
                ->where('jalur_pendaftaran_id', $jalur->id)
                ->where('status', 'verified')
                ->whereNotIn('id', $acceptedIds)
                ->orderBy('nilai_rata_rata', 'desc')
                ->get();

            $previewData[$jalur->id] = [
                'jalur' => $jalur,
                'accepted' => $accepted,
                'rejected' => $rejected,
            ];
        }

        Cache::put('selection_preview_data', $previewData, now()->addHours(24));

        return redirect()->route('admin.selection.index')->with('success', 'Preview seleksi berhasil dibuat.');
    }

    public function publish(Request $request)
    {
        $previewData = Cache::get('selection_preview_data');

        if (!$previewData) {
            return redirect()->route('admin.selection.index')->with('error', 'Tidak ada data preview untuk dipublikasikan. Jalankan seleksi terlebih dahulu.');
        }

        $totalAccepted = 0;
        $totalRejected = 0;

        foreach ($previewData as $data) {
            $acceptedIds = $data['accepted']->pluck('id');
            $rejectedIds = $data['rejected']->pluck('id');

            Registration::whereIn('id', $acceptedIds)->update(['status' => 'diterima']);
            Registration::whereIn('id', $rejectedIds)->update(['status' => 'tidak_diterima']);

            $totalAccepted += $acceptedIds->count();
            $totalRejected += $rejectedIds->count();
        }

        // Clear cache
        Cache::forget('selection_preview_data');

        return redirect()->route('admin.selection.index')
            ->with('success', "Hasil seleksi berhasil dipublikasikan! {$totalAccepted} siswa diterima, {$totalRejected} siswa tidak diterima. Siswa sudah dapat melihat hasilnya di dashboard masing-masing.");
    }

    public function reset(Request $request)
    {
        // Reset all published results back to verified
        Registration::where('status', 'diterima')->update(['status' => 'verified']);
        Registration::where('status', 'tidak_diterima')->update(['status' => 'verified']);

        // Clear any preview cache
        Cache::forget('selection_preview_data');

        return redirect()->route('admin.selection.index')
            ->with('success', 'Hasil seleksi telah direset. Semua status dikembalikan ke "verified". Anda dapat menjalankan seleksi ulang.');
    }

    public function exportAll()
    {
        return Excel::download(new RegistrationsExport(), 'Data-Siswa-Diterima-Semua-Jalur.xlsx');
    }

    public function exportByJalur($jalurId)
    {
        $jalur = JalurPendaftaran::findOrFail($jalurId);
        $filename = 'Data-Siswa-Diterima-Jalur-' . str_replace(' ', '-', $jalur->nama_jalur) . '.xlsx';
        return Excel::download(new RegistrationsExport($jalurId), $filename);
    }
}
