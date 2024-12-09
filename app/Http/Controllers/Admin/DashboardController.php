<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\Jadwal;
use App\Models\Absen;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $data = [
            'totalUsers' => User::where('role', 'user')->count(),
            'totalLokasi' => Lokasi::count(),
            'activeJadwals' => Jadwal::where('status', 'active')->count(),
            'todayAbsens' => Absen::whereDate('created_at', $today)->count(),
            'recentJadwals' => Jadwal::with(['lokasi.users'])
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get(),
        ];

        return view('admin.dashboard', $data);
    }
}
