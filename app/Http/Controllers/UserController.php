<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $users = User::where('role', 'user')
                        ->with('lokasi')
                        ->get();
        } else {
            // Get users only from locations where petugas has access
            $lokasiIds = DB::table('petugas_lokasis')
                ->where('user_id', $user->id)
                ->pluck('lokasi_id');

            $users = User::where('role', 'user')
                        ->whereIn('lokasi_id', $lokasiIds)
                        ->with('lokasi')
                        ->get();
        }

        return view('users.index', compact('users'));
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

        return view('users.create', compact('lokasis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'lokasi_id' => 'required|exists:lokasis,id',
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

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'lokasi_id' => $validated['lokasi_id'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Pegawai berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $currentUser = Auth::user();

        if (!$currentUser->isAdmin()) {
            // Check if petugas has access to this user's location
            $hasAccess = DB::table('petugas_lokasis')
                ->where('user_id', $currentUser->id)
                ->where('lokasi_id', $user->lokasi_id)
                ->exists();

            if (!$hasAccess) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit pegawai ini');
            }
        }

        // Get available locations based on access
        if ($currentUser->isAdmin()) {
            $lokasis = Lokasi::all();
        } else {
            $lokasiIds = DB::table('petugas_lokasis')
                ->where('user_id', $currentUser->id)
                ->pluck('lokasi_id');
            $lokasis = Lokasi::whereIn('id', $lokasiIds)->get();
        }

        return view('users.edit', compact('user', 'lokasis'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'lokasi_id' => 'required|exists:lokasis,id',
            'password' => 'nullable|string|min:8',
        ]);

        $currentUser = Auth::user();

        if (!$currentUser->isAdmin()) {
            // Check if petugas has access to both current and new location
            $hasAccess = DB::table('petugas_lokasis')
                ->where('user_id', $currentUser->id)
                ->whereIn('lokasi_id', [$user->lokasi_id, $validated['lokasi_id']])
                ->count() == 2; // Must have access to both locations

            if (!$hasAccess) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit pegawai ini');
            }
        }

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'lokasi_id' => $validated['lokasi_id'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        return redirect()->route('users.index')
            ->with('success', 'Data pegawai berhasil diupdate');
    }

    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        if (!$currentUser->isAdmin()) {
            // Check if petugas has access to this user's location
            $hasAccess = DB::table('petugas_lokasis')
                ->where('user_id', $currentUser->id)
                ->where('lokasi_id', $user->lokasi_id)
                ->exists();

            if (!$hasAccess) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus pegawai ini');
            }
        }

        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'Pegawai berhasil dihapus');
    }
}

