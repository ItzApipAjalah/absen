<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Absen;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $user = Auth::user();

        // Get lokasi_ids from petugas_lokasis table
        $lokasiIds = DB::table('petugas_lokasis')
            ->where('user_id', $user->id)
            ->pluck('lokasi_id');

        $data = [
            // Count active jadwals only from petugas's locations
            'activeJadwals' => Jadwal::whereIn('lokasi_id', $lokasiIds)
                                ->where('status', 'active')
                                ->count(),

            // Count absens only from petugas's locations
            'todayAbsens' => Absen::whereDate('created_at', $today)
                                ->whereHas('jadwal', function($query) use ($lokasiIds) {
                                    $query->whereIn('lokasi_id', $lokasiIds);
                                })
                                ->count(),

            // Count late absens only from petugas's locations
            'todayLateCount' => Absen::whereDate('created_at', $today)
                                ->where('is_telat', true)
                                ->whereHas('jadwal', function($query) use ($lokasiIds) {
                                    $query->whereIn('lokasi_id', $lokasiIds);
                                })
                                ->count(),

            // Get recent jadwals only from petugas's locations
            'recentJadwals' => Jadwal::whereIn('lokasi_id', $lokasiIds)
                                ->with(['lokasi.users'])
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get(),

            // Get late absens only from petugas's locations
            'todayLateAbsens' => Absen::with(['jadwal.lokasi.users'])
                                ->whereDate('created_at', $today)
                                ->where('is_telat', true)
                                ->whereHas('jadwal', function($query) use ($lokasiIds) {
                                    $query->whereIn('lokasi_id', $lokasiIds);
                                })
                                ->orderBy('durasi_telat', 'desc')
                                ->take(5)
                                ->get(),
        ];

        return view('petugas.dashboard', $data);
    }
}
