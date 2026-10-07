@extends('layouts.app')

@section('title', 'Laporan Peminjaman Alat')

@section('content')
<div class="max-w-6xl mx-auto py-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Laporan Riwayat Peminjaman</h3>

        <!-- Form Filter Tanggal & Tombol Cetak -->
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex space-x-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                        Filter
                    </button>

                    <a href="{{ route('admin.laporan.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold transition">
                        Reset
                    </a>

                    <!-- Tombol Cetak PDF dengan membawa parameter filter -->
                    <a href="{{ route('admin.laporan.pdf', ['tgl_mulai' => request('tgl_mulai'), 'tgl_selesai' => request('tgl_selesai')]) }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center justify-center">
                        Cetak PDF
                    </a>
                </div>
            </div>
        </form>

        <!-- Tabel Riwayat Peminjaman -->
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-100 text-xs uppercase text-gray-700 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Peminjam</th>
                        <th class="px-4 py-3">Alat Dipinjam</th>
                        <th class="px-4 py-3">Tgl Pinjam</th>
                        <th class="px-4 py-3">Tgl Kembali</th>
                        <th class="px-4 py-3">Kondisi</th>
                        <th class="px-4 py-3">Denda</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($riwayat as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $item->user->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <ul class="list-disc list-inside">
                                    @foreach($item->detailPinjam as $detail)
                                        <li>{{ $detail->alat->nama_alat ?? 'Alat Dihapus' }} ({{ $detail->jumlah }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-4 py-3">{{ $item->tgl_pinjam }}</td>
                            <td class="px-4 py-3">{{ $item->pengembalian->tgl_kembali ?? $item->tgl_kembali_plan }}</td>
                            <td class="px-4 py-3 capitalize">{{ $item->pengembalian->kondisi_kembali ?? '-' }}</td>
                            <td class="px-4 py-3">
                                Rp {{ number_format($item->pengembalian->denda ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded-md {{ $item->status == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-4 text-center text-gray-400">Tidak ada data riwayat peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection