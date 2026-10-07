    @extends('layouts.app')

    @section('title', 'Dashboard Admin - Sistem Peminjaman')
    @section('header-title', 'Ringkasan Aktivitas Sistem')

    @section('content')
        <!-- Alert Selamat Datang -->
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg shadow-sm">
            Selamat datang, <strong class="font-semibold">{{ auth()->user()->name }}</strong>! Anda login sebagai hak akses
            <span class="uppercase font-bold text-emerald-900">{{ auth()->user()->role }}</span>.
        </div>

        <!-- Metric / Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Alat -->
        <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Alat</p>
                <h4 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalAlat ?? 0 }}</h4>
                <p class="text-xs text-emerald-600 mt-1 font-medium">
                    <span class="font-semibold">{{ $alatBaik ?? 0 }}</span> kondisi baik
                </p>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            </div>
        </div>

        <!-- Total Kategori -->
        <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori Alat</p>
                <h4 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalKategori ?? 0 }}</h4>
                <p class="text-xs text-gray-500 mt-1">Jenis kategori terdaftar</p>
            </div>
            <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
            </div>
        </div>

        <!-- Peminjaman Aktif -->
        <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sedang Dipinjam</p>
                <h4 class="text-2xl font-bold text-gray-800 mt-1">{{ $peminjamanAktif ?? 0 }}</h4>
                <p class="text-xs text-amber-600 mt-1 font-medium">
                    <span class="font-semibold">{{ $peminjamanDiajukan ?? 0 }}</span> menunggu persetujuan
                </p>
            </div>
            <div class="p-3 bg-amber-50 text-amber-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pengguna</p>
                <h4 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalUser ?? 0 }}</h4>
                <p class="text-xs text-gray-500 mt-1">Siswa, Petugas & Admin</p>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 43a8 8 0 100-16 8 8 0 000 16zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Main Content Layout (Table & Recent Activity) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tabel Peminjaman Terbaru (2 Kolom) -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Transaksi Peminjaman Terbaru</h3>
                <a href="{{ route('admin.peminjaman.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                    Lihat Semua &rarr;
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">
                            <th class="py-3 px-4 border-b">Peminjam</th>
                            <th class="py-3 px-4 border-b">Tgl Pinjam</th>
                            <th class="py-3 px-4 border-b">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        @forelse($peminjamanTerbaru ?? [] as $peminjaman)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 border-b font-medium text-gray-900">
                                    {{ $peminjaman->user->name ?? 'User Dihapus' }}
                                </td>
                                <td class="py-3 px-4 border-b text-xs text-gray-600">
                                    {{ $peminjaman->tgl_pinjam }}
                                </td>
                                <td class="py-3 px-4 border-b">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                                        @if($peminjaman->status == 'diajukan') bg-yellow-100 text-yellow-800
                                        @elseif($peminjaman->status == 'dipinjam') bg-blue-100 text-blue-800
                                        @elseif($peminjaman->status == 'dikembalikan') bg-emerald-100 text-emerald-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-500">Belum ada transaksi peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Log Aktivitas Ringkasan (1 Kolom) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Aktivitas Terkini</h3>
                <a href="{{ route('admin.logAktivitas.index') ?? '#' }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                    Lihat Log
                </a>
            </div>
            <div class="p-4 divide-y divide-gray-100">
                @forelse($logsTerbaru ?? [] as $log)
                    <div class="py-3 first:pt-0 last:pb-0">
                        <div class="flex justify-between items-center text-xs text-gray-500 mb-1">
                            <span class="font-semibold text-gray-800">{{ $log->user->name ?? 'Sistem' }}</span>
                            <span>{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $log->aktivitas }}</p>
                    </div>
                @empty
                    <p class="text-sm text-center text-gray-500 py-4">Belum ada aktivitas tercatat.</p>
                @endforelse
            </div>
        </div>
    </div>
    @endsection 
    
