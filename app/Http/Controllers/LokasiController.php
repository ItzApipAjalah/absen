<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LokasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $lokasis = Lokasi::with(['users', 'petugas'])->get();
        } else {
            $lokasis = $user->managedLokasis()
                ->with(['users', 'petugas'])
                ->get();
        }

        return view('lokasi.index', compact('lokasis'));
    }

    public function create()
    {
        return view('lokasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $lokasi = Lokasi::create($validated);

        // If creator is petugas, add them as creator
        if (Auth::user()->isPetugas()) {
            $lokasi->petugas()->attach(Auth::id(), ['access_type' => 'creator']);
        }

        return redirect()->route('lokasi.index')
            ->with('success', 'Lokasi berhasil ditambahkan');
    }

    public function edit(Lokasi $lokasi)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            $hasAccess = DB::table('petugas_lokasis')
                ->where('user_id', $user->id)
                ->where('lokasi_id', $lokasi->id)
                ->exists();

            if (!$hasAccess) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit lokasi ini');
            }
        }

        return view('lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            $hasAccess = DB::table('petugas_lokasis')
                ->where('user_id', $user->id)
                ->where('lokasi_id', $lokasi->id)
                ->exists();

            if (!$hasAccess) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit lokasi ini');
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $lokasi->update($validated);

        return redirect()->route('lokasi.index')
            ->with('success', 'Lokasi berhasil diupdate');
    }

    public function destroy(Lokasi $lokasi)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            $hasAccess = DB::table('petugas_lokasis')
                ->where('user_id', $user->id)
                ->where('lokasi_id', $lokasi->id)
                ->exists();

            if (!$hasAccess) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus lokasi ini');
            }
        }

        $lokasi->delete();
        return redirect()->route('lokasi.index')
            ->with('success', 'Lokasi berhasil dihapus');
    }

    public function assign(Lokasi $lokasi)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Hanya admin yang dapat mengassign petugas');
        }

        $petugas = User::where('role', 'petugas')->get();
        return view('lokasi.assign', compact('lokasi', 'petugas'));
    }

    public function assignPetugas(Request $request, Lokasi $lokasi)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Hanya admin yang dapat mengassign petugas');
        }

        $validated = $request->validate([
            'petugas_ids' => 'required|array',
            'petugas_ids.*' => 'exists:users,id'
        ]);

        // Remove existing assigned petugas (not creators)
        $lokasi->petugas()
            ->wherePivot('access_type', 'assigned')
            ->detach();

        // Attach new petugas
        foreach ($validated['petugas_ids'] as $petugasId) {
            // Don't duplicate if they're already a creator
            if (!$lokasi->petugas()
                ->wherePivot('access_type', 'creator')
                ->where('users.id', $petugasId)
                ->exists()) {
                $lokasi->petugas()->attach($petugasId, ['access_type' => 'assigned']);
            }
        }

        return redirect()->route('lokasi.index')
            ->with('success', 'Petugas berhasil ditugaskan');
    }
}
