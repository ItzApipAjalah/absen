@extends('layouts.app')

@section('title', 'Manajemen Pegawai')

@section('content')
<div class="space-y-6">
    <div class="bg-gray-800 rounded-lg shadow-lg">
        <div class="p-4 border-b border-gray-700 flex justify-between items-center">
            <h5 class="font-semibold text-xl">Manajemen Pegawai</h5>
            <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Tambah Pegawai
            </a>
        </div>
        <div class="p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-500 text-white px-4 py-2 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Lokasi</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-700">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">{{ $user->lokasi->name ?? '-' }}</td>
                            <td class="px-4 py-2">
                                @php
                                    $hasAccess = Auth::user()->isAdmin() ||
                                        DB::table('petugas_lokasis')
                                            ->where('user_id', Auth::id())
                                            ->where('lokasi_id', $user->lokasi_id)
                                            ->exists();
                                @endphp

                                @if($hasAccess)
                                <div class="flex space-x-2">
                                    <a href="{{ route('users.edit', $user->id) }}"
                                       class="inline-flex items-center px-2 py-1 bg-yellow-600 rounded hover:bg-yellow-700 transition-colors text-sm">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}"
                                          method="POST"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-2 py-1 bg-red-600 rounded hover:bg-red-700 transition-colors text-sm"
                                                onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
