<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Registration;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('welcome', compact('announcements'));
    }

    public function cekStatus(Request $request)
    {
        $request->validate([
            'nomor_pendaftaran' => 'required|string',
        ]);

        $registration = Registration::with(['student.user', 'jalur_pendaftaran'])
            ->where('no_pendaftaran', $request->nomor_pendaftaran)
            ->first();

        if (!$registration) {
            return back()->with('error', 'Nomor pendaftaran tidak ditemukan.');
        }

        return back()->with('status_check', $registration);
    }
}
