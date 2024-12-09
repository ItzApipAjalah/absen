<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Jadwal;
use App\Models\Absenharian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    public function checkIn(Request $request, Jadwal $jadwal)
    {
        $now = Carbon::now();
        $startTime = Carbon::parse($jadwal->start_time);

        $isLate = $now->diffInMinutes($startTime) > $jadwal->late_tolerance;
        $durasiTelat = $isLate ? $now->diffInMinutes($startTime) : 0;

        $absen = Absen::create([
            'jadwal_id' => $jadwal->id,
            'check_in_time' => $now->format('H:i:s'),
            'is_telat' => $isLate,
            'durasi_telat' => $durasiTelat
        ]);

        // Update or create daily attendance record
        $absenharian = Absenharian::firstOrCreate(
            [
                'user_id' => $jadwal->user_id,
                'date' => $now->format('Y-m-d')
            ],
            [
                'total_check_in' => 0,
                'total_time' => 0,
                'total_late' => 0
            ]
        );

        $absenharian->increment('total_check_in');
        if ($isLate) {
            $absenharian->increment('total_late', $durasiTelat);
        }

        return response()->json([
            'message' => 'Check-in successful',
            'data' => $absen
        ]);
    }

    public function checkOut(Request $request, Jadwal $jadwal)
    {
        $absen = $jadwal->absen;
        $now = Carbon::now();

        $absen->update([
            'check_out_time' => $now->format('H:i:s')
        ]);

        // Calculate total time
        $checkIn = Carbon::parse($absen->check_in_time);
        $checkOut = Carbon::parse($absen->check_out_time);
        $totalMinutes = $checkOut->diffInMinutes($checkIn);

        // Update daily total time
        $absenharian = Absenharian::where('user_id', $jadwal->user_id)
            ->where('date', $now->format('Y-m-d'))
            ->first();

        $absenharian->increment('total_time', $totalMinutes);

        return response()->json([
            'message' => 'Check-out successful',
            'data' => $absen
        ]);
    }
}
