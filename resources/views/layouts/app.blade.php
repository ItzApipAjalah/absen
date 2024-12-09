<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Absensi')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-900 text-gray-100">
    <!-- Sidebar -->
    <div class="min-h-screen flex">
        <div class="fixed w-64 h-screen bg-gray-800 shadow-lg">
            <div class="flex items-center justify-center h-16 border-b border-gray-700">
                <span class="text-lg font-semibold text-white">Sistem Absensi</span>
            </div>
            <nav class="mt-4">
                @auth
                    <div class="px-4 py-3 border-b border-gray-700">
                        <p class="text-sm text-gray-400">Welcome,</p>
                        <p class="font-medium">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="px-4 py-2">
                        <div class="space-y-2">
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-home w-5 h-5 mr-3"></i>
                                    Dashboard
                                </a>
                            @endif
                            @if(Auth::user()->isPetugas())
                                <a href="{{ route('petugas.dashboard') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-home w-5 h-5 mr-3"></i>
                                    Dashboard
                                </a>
                            @endif
                            @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                                <a href="{{ route('jadwal.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-calendar w-5 h-5 mr-3"></i>
                                    Jadwal
                                </a>
                                <a href="{{ route('lokasi.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-map-marker-alt w-5 h-5 mr-3"></i>
                                    Lokasi
                                </a>
                                <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-users w-5 h-5 mr-3"></i>
                                    Pegawai
                                </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="flex w-full items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <div class="p-8">
                @if(session('success'))
                    <div class="mb-4 bg-green-500 text-white px-4 py-2 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 bg-red-500 text-white px-4 py-2 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
