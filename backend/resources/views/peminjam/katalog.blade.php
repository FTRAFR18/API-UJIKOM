@extends('layouts.app')

@section('title', 'Katalog Alat - Panel Peminjam')
@section('header-title', 'Katalog Alat Tersedia')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Pesan Sukses / Error -->
    @if(session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="mb-6">
        <p class="text-gray-500 text-sm">Silakan pilih alat yang ingin dipinjam, tentukan jumlahnya, dan ajukan peminjaman.</p>
    </div>

    <form action="{{ route('peminjam.peminjaman.ajukan') }}" method="POST">
        @csrf

        <!-- Input Rencana Tanggal Kembali -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Rencana Tanggal Kembali</label>
            <input type="date" name="tgl_kembali_plan" class="w-full md:w-1/3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2 border" required>
        </div>

        <!-- Grid Katalog -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @forelse($alats as $index => $alat)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col">
                    
                    <!-- Gambar Alat -->
                    <!-- Pastikan Anda memiliki kolom 'gambar' di database tabel alats -->
                    <div class="h-48 w-full bg-gray-100 flex items-center justify-center overflow-hidden relative">
                        @if($alat->gambar)
                            <img src="{{ asset('storage/' . $alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="object-cover w-full h-full">
                        @else
                            <!-- Placeholder jika tidak ada gambar -->
                            <img src="https://via.placeholder.com/400x300?text=Tidak+Ada+Gambar" alt="Placeholder" class="object-cover w-full h-full opacity-60">
                        @endif
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex justify-between items-start mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                {{ $alat->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $alat->stok > 0 ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                Stok: {{ $alat->stok }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $alat->nama_alat }}</h3>
                        
                        <div class="mt-auto pt-4 mt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between gap-2">
                                <label class="flex items-center space-x-2 cursor-pointer group">
                                    <input type="checkbox" name="alat_id[]" value="{{ $alat->id }}" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ $alat->stok <= 0 ? 'disabled' : '' }}>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-600 transition">Pilih</span>
                                </label>

                                <div class="flex items-center space-x-2">
                                    <label class="text-xs text-gray-500 font-medium">Jumlah</label>
                                    <input type="number" name="jumlah[]" value="1" min="1" max="{{ $alat->stok }}" class="w-16 text-center text-sm rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-1.5 px-2 bg-gray-50" {{ $alat->stok <= 0 ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 py-12 text-center flex flex-col items-center">
                        <svg class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Belum Ada Alat</h3>
                        <p class="mt-1 text-sm text-gray-500">Katalog alat sedang kosong atau belum ditambahkan oleh Admin.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if(count($alats) > 0)
        <!-- Menggunakan flex biasa tanpa class sticky atau fixed -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6 flex justify-end mt-8 mb-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full shadow-md transition-transform transform hover:-translate-y-1 flex items-center">
            Ajukan Peminjaman
            </button>
        </div>
        @endif
    </form>
</div>
@endsection