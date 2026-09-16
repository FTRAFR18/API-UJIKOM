@extends('layouts.app')

@section('title', 'Persetujuan Peminjaman - Dashboard Petugas')
@section('header-title', 'Daftar Pengajuan Peminjaman Alat')

@section('content')
    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg shadow-sm text-sm">
            {{ session('success') }}
        </div>
    @endif(session('error'))
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg shadow-sm text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
        <div class="p-5 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Menunggu Verifikasi Persetujuan</h3>
            <form action="{{ route('petugas.peminjaman.index') }}" method="GET" class="flex w-full md:w-80">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam..."
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 text-sm font-semibold rounded-r-lg transition">Cari</button>
                @if(request('search'))
                    <a href="{{ route('petugas.peminjaman.index') }}"
                    class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-2 text-sm rounded-lg flex items-center transition">Reset</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead></thead>
            </table>
        </div>
    </div>