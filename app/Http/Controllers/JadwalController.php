<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $jadwals = Jadwal::with(['lokasi.users'])->get();
            $lokasis = Lokasi::all();
        } else {
            // Get lokasi_ids from petugas_lokasis table
            $lokasiIds = DB::table('petugas_lokasis')
                ->where('user_id', $user->id)
                ->pluck('lokasi_id');

            // Get jadwals for those locations
            $jadwals = Jadwal::whereIn('lokasi_id', $lokasiIds)
                ->with(['lokasi.users'])
                ->get();

            // Get locations for dropdown
            $lokasis = Lokasi::whereIn('id', $lokasiIds)->get();
        }

        return view('jadwal.index', compact('jadwals', 'lokasis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lokasi_id' => 'required|exists:lokasis,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required',
            'end_time' => 'required',
            'late_tolerance' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $user = Auth::user();

        if (!$user->isAdmin()) {
            // Check if petugas has access to this location
            $hasAccess = DB::table('petugas_lokasis')
                ->where('user_id', $user->id)
                ->where('lokasi_id', $validated['lokasi_id'])
                ->exists();

            if (!$hasAccess) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke lokasi ini');
            }
        }

        Jadwal::create($validated);

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $validated = $request->validate([
            'lokasi_id' => 'required|exists:lokasis,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required',
            'end_time' => 'required',
            'late_tolerance' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $user = Auth::user();

        if (!$user->isAdmin()) {
            // Check if petugas has access to this location
            $hasAccess = DB::table('petugas_lokasis')
                ->where('user_id', $user->id)
                ->where('lokasi_id', $validated['lokasi_id'])
                ->exists();

            if (!$hasAccess) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit jadwal ini');
            }
        }

        $jadwal->update($validated);

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy(Jadwal $jadwal)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            // Check if petugas has access to this location
            $hasAccess = DB::table('petugas_lokasis')
                ->where('user_id', $user->id)
                ->where('lokasi_id', $jadwal->lokasi_id)
                ->exists();

            if (!$hasAccess) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus jadwal ini');
            }
        }

        $jadwal->delete();
        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }

    public function show(Jadwal $jadwal)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            // Check if petugas has access to this location
            $hasAccess = DB::table('petugas_lokasis')
                ->where('user_id', $user->id)
                ->where('lokasi_id', $jadwal->lokasi_id)
                ->exists();

            if (!$hasAccess) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }

        return response()->json($jadwal->load('lokasi'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $lokasis = Lokasi::all();
        } else {
            $lokasiIds = DB::table('petugas_lokasis')
                ->where('user_id', $user->id)
                ->pluck('lokasi_id');
            $lokasis = Lokasi::whereIn('id', $lokasiIds)->get();
        }

        return view('jadwal.create', compact('lokasis'));
    }

    public function edit(Jadwal $jadwal)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            $hasAccess = DB::table('petugas_lokasis')
                ->where('user_id', $user->id)
                ->where('lokasi_id', $jadwal->lokasi_id)
                ->exists();

            if (!$hasAccess) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit jadwal ini');
            }
        }

        if ($user->isAdmin()) {
            $lokasis = Lokasi::all();
        } else {
            $lokasiIds = DB::table('petugas_lokasis')
                ->where('user_id', $user->id)
                ->pluck('lokasi_id');
            $lokasis = Lokasi::whereIn('id', $lokasiIds)->get();
        }

        return view('jadwal.edit', compact('jadwal', 'lokasis'));
    }
}
