<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Document;

class PanitiaController extends Controller
{
    public function index(Request $request)
    {
        $query = Registration::with(['student', 'jalurPendaftaran'])->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            })->orWhere('no_pendaftaran', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $registrations = $query->paginate(10);

        return view('panitia.dashboard', compact('registrations'));
    }

    public function show($id)
    {
        $registration = Registration::with(['student', 'documents', 'jalurPendaftaran'])->findOrFail($id);
        return view('panitia.show', compact('registration'));
    }

    public function verifyDocument(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan' => 'required_if:status,rejected',
        ]);

        $document = Document::findOrFail($id);
        $document->update([
            'status' => $request->status,
            'catatan' => $request->status === 'rejected' ? $request->catatan : null,
        ]);

        $registration = $document->registration;

        // Check if all required documents are approved
        $requiredDocs = ['foto_3x4', 'kartu_keluarga', 'ijazah', 'rapor', 'piagam_prestasi'];
        $approvedDocs = $registration->documents()->where('status', 'approved')->pluck('tipe_dokumen')->toArray();
        
        $allApproved = count(array_intersect($requiredDocs, $approvedDocs)) === count($requiredDocs);

        if ($allApproved) {
            $registration->update(['status' => 'verified']);
        } elseif ($registration->status === 'verified') {
            $registration->update(['status' => 'pending']);
        }

        return back()->with('success', 'Status dokumen berhasil diperbarui.');
    }
}
