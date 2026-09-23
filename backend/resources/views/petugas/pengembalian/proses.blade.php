@extends('layouts.app')

@section('title', 'Proses Pengembalian')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="mb-4">
        <a href="{{ route('petugas.pengembalian.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition">
            &larr; Kembali ke Daftar Pengembalian
        </a>
    </div>

    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Form Pengembalian Alat</h3>
            <p class="text-xs text-gray-500">Periksa detail transaksi dan masukkan kondisi serta denda jika ada.</p>
        </div>

        <!-- Detail Peminjaman & Rincian Alat dari Database -->
        <div class="p-6 border-b border-gray-200 bg-gray-50/50 space-y-4 text-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="block text-gray-500 text-xs">Peminjam</span>
                    <span class="font-bold text-gray-900">{{ $peminjaman->user->name ?? 'User Tidak Ditemukan' }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 text-xs">Tanggal Pinjam</span>
                    <span class="font-medium text-gray-800">{{ $peminjaman->tgl_pinjam }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 text-xs">Batas Tanggal Kembali</span>
                    <span class="font-medium text-gray-800">{{ $peminjaman->tgl_kembali_plan }}</span>
                </div>
            </div>

            <div>
                <span class="block text-gray-500 text-xs mb-2">Detail Alat yang Dipinjam</span>
                <div class="border border-gray-200 rounded-lg overflow-hidden bg-white">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-100 text-xs uppercase text-gray-700">
                            <tr>
                                <th class="px-4 py-2">Nama Alat</th>
                                <th class="px-4 py-2 text-center">Jumlah Dipinjam</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($peminjaman->detailPinjam as $detail)
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-900">
                                        {{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-md">
                                            {{ $detail->jumlah }} unit
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-center text-gray-400">Tidak ada detail alat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form Input Pengembalian -->
        <form action="{{ route('petugas.pengembalian.proses', $peminjaman->id) }}" method="POST" class="p-6 space-y-4">
            @csrf

            <div>
                <label for="kondisi_kembali" class="block text-sm font-semibold text-gray-700 mb-1">
                    Kondisi Kembali <span class="text-red-500">*</span>
                </label>
                <select name="kondisi_kembali" id="kondisi_kembali" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kondisi_kembali') border-red-500 @enderror" 
                    required>
                    <option value="">-- Pilih Kondisi --</option>
                    <option value="baik" {{ old('kondisi_kembali') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak ringan" {{ old('kondisi_kembali') == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak sedang" {{ old('kondisi_kembali') == 'rusak sedang' ? 'selected' : '' }}>Rusak Sedang</option>
                    <option value="rusak berat" {{ old('kondisi_kembali') == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
                @error('kondisi_kembali')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="denda" class="block text-sm font-semibold text-gray-700 mb-1">
                    Denda (Rp)
                </label>
                <input type="number" name="denda" id="denda" min="0" 
                    value="{{ old('denda', 0) }}" 
                    placeholder="0" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('denda') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">
                    * Kosongkan atau isi 0 jika tidak ada denda tambahan. Jika ada keterlambatan, sistem otomatis menambahkan Rp 1.000/hari.
                </p>
                @error('denda')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="pt-4 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('petugas.pengembalian.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-semibold transition">
                    Simpan Pengembalian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection