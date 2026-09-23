<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman Alat</title>
    <style>
        body { font-family: sans-serif; font-size: 11pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { text-align: left ; margin: 5px 0 0 0; font-size: 9pt; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #333; }
        th { background-color: #f2f2f2; padding: 8px; font-size: 10pt; }
        td { padding: 6px; font-size: 9pt; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PEMINJAMAN ALAT</h2>
        @if($tglMulai && $tglSelesai)
            <p>Periode: {{ $tglMulai }} s/d {{ $tglSelesai }}</p>
        @else
            <p>Periode: Semua Riwayat</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Detail Alat</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Kondisi</th>
                <th>Denda</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>
                        @foreach($item->detailPinjam as $detail)
                            - {{ $detail->alat->nama_alat ?? 'Alat Dihapus' }} ({{ $detail->jumlah }} unit)<br>
                        @endforeach
                    </td>
                    <td class="text-center">{{ $item->tgl_pinjam }}</td>
                    <td class="text-center">{{ $item->pengembalian->tgl_kembali ?? $item->tgl_kembali_plan }}</td>
                    <td class="text-center">{{ ucfirst($item->pengembalian->kondisi_kembali ?? '-') }}</td>
                    <td>Rp {{ number_format($item->pengembalian->denda ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">{{ ucfirst($item->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>