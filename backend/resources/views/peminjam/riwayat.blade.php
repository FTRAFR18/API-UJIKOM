@extends('layouts.app')

@section('title', 'Riwayat Peminjaman - Panel Peminjam')
@section('header-title', 'Riwayat Peminjaman Saya')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Pesan Sukses/Error -->
    @if(session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm flex items-center">
            <svg class="h-5 w-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-200 bg-white">
            <h2 class="text-lg font-bold text-gray-800">Daftar Transaksi Anda</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau status peminjaman alat yang telah Anda ajukan di sini.</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Alat</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rencana Kembali</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    
                    @forelse($peminjamans as $peminjaman)
                        <tr class="hover:bg-gray-50 transition-colors">
                            
                            <!-- Tanggal Pengajuan (tgl_pinjam) -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d M Y, H:i') }}
                            </td>
                            
                            <!-- Detail Alat yang Dipinjam (relasi detailPinjam) -->
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($peminjaman->detailPinjam as $detail)
                                        <li>
                                            <span class="font-medium text-gray-900">{{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}</span> 
                                            <span class="text-gray-500">({{ $detail->jumlah }} unit)</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            
                            <!-- Rencana Pengembalian (tgl_kembali_plan) -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($peminjaman->tgl_kembali_plan)->format('d M Y') }}
                            </td>
                            
                            <!-- Status Label (Badge) -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(in_array(strtolower($peminjaman->status), ['diajukan']))
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        Sedang Diajukan
                                    </span>
                                @elseif(in_array(strtolower($peminjaman->status), ['dipinjam']))
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                        Sedang Dipinjam
                                    </span>
                                @elseif(in_array(strtolower($peminjaman->status), ['dikembalikan']))
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                        Sudah Dikembalikan
                                    </span>
                                @elseif(in_array(strtolower($peminjaman->status), ['ditolak']))
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <!-- Tampilan Jika Belum Ada Riwayat -->
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Riwayat Peminjaman</h3>
                                    <p class="text-sm mb-4">Anda belum pernah mengajukan peminjaman alat sebelumnya.</p>
                                    <a href="{{ route('peminjam.katalog') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                                        Lihat Katalog Alat
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection