<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Registration::count(),
            'verified' => Registration::where('status', 'verified')->count(),
            'accepted' => Registration::where('status', 'diterima')->count(),
            'rejected' => Registration::where('status', 'tidak_diterima')->count(),
        ];

        // Daily Registrations for the last 30 days
        $dailyRegistrations = Registration::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $labels = $dailyRegistrations->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('d M');
        });
        
        $data = $dailyRegistrations->pluck('total');

        return view('admin.dashboard', compact('stats', 'labels', 'data'));
    }
}
