<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class PetugasController extends Controller
{
    // Menampilkan daftar pengajuan peminjaman dari siswa/peminjam
    public function indexPeminjaman(Request $request)
    {
        $search = $request->input('search');

        $peminjamans = Peminjaman::with(['user', 'detailPinjam.alat'])
            ->where('status', 'diajukan')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('petugas.peminjaman.index', compact('peminjamans', 'search'));
    }

    // Menyetujui Peminjaman (Mengubah status & mengurangi stok alat)
    public function setujuiPeminjaman($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with('detailPinjam')->findOrFail($id);
            $peminjaman->update(['status' => 'dipinjam']);

            // Kurangi stok alat secara otomatis
            foreach ($peminjaman->detailPinjam as $detail) {
                $alat = Alat::findOrFail($detail->alat_id);
                $alat->stok -= $detail->jumlah;
                $alat->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Peminjaman disetujui dan stok alat dikurangi.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Menolak Peminjaman (Menghapus pengajuan agar bisa mengajukan ulang)
    public function tolakPeminjaman($id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Pastikan statusnya memang masih diajukan
            if ($peminjaman->status == 'diajukan') {
                $peminjaman->delete();
                return redirect()->back()->with('success', 'Pengajuan peminjaman berhasil ditolak.');
            }

            return redirect()->back()->with('error', 'Status peminjaman sudah berubah.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function indexPengembalian(Request $request)
    {
        $search = $request->input('search');

        $peminjamans = Peminjaman::with(['user', 'detailPinjam.alat'])
            ->where('status', 'dipinjam')
            ->orWhere('status', 'telat')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('petugas.pengembalian.index', compact('peminjamans', 'search'));       
    }

    public function createPengembalian($id)
{
    $peminjaman = Peminjaman::with(['user', 'detailPinjam.alat'])->findOrFail($id);
    
    return view('petugas.pengembalian.proses', compact('peminjaman'));
}

    public function prosesPengembalian(Request $request, $peminjamanId)
    {
        $request->validate([
            'kondisi_kembali' => 'required|string',
            'denda' => 'nullable|integer',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with('detailPinjam')->findOrFail($peminjamanId);

            // 1. Hitung Denda Keterlambatan Otomatis (Rp 1.000 / Hari)
            $tglSekarang = Carbon::now()->startOfDay();
            $tglRencana  = Carbon::parse($peminjaman->tgl_kembali_plan)->startOfDay();
            
            $dendaKeterlambatan = 0;
            if ($tglSekarang->greaterThan($tglRencana)) {
                $hariTerlambat = $tglRencana->diffInDays($tglSekarang);
                $dendaKeterlambatan = $hariTerlambat * 1000;
            }

            // 2. Gabungkan denda keterlambatan dengan denda yang diinput petugas
            $dendaInput = $request->denda ?? 0;
            $totalDenda = $dendaKeterlambatan + $dendaInput;

            // Simpan data pengembalian
            Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'tgl_kembali' => now(),
                'kondisi_kembali' => $request->kondisi_kembali,
                'denda' => $totalDenda,
                'petugas_id' => auth()->id(),
            ]);

            // Update status peminjaman jadi selesai
            $peminjaman->update(['status' => 'dikembalikan']);

            // Kembaliakan stok alat ke inventaris 
            foreach ($peminjaman->detailPinjam as $detail) {
                $alat = Alat::findOrFail($detail->alat_id);
                $alat->stok += $detail->jumlah;
                $alat->save();
            }

            DB::commit();
            return redirect()->route('petugas.pengembalian.index')->with('success', 'Pengembalian berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function indexLaporan(Request $request)
    {
        $query = Peminjaman::with(['user', 'detailPinjam.alat', 'pengembalian'])
            ->where('status', 'dikembalikan');

        // Filter berdasarkan tanggal pinjam
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('tgl_pinjam', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        $riwayat = $query->latest()->get();

        return view('petugas.laporan.index', compact('riwayat'));
    }

    public function cetakPdf(Request $request)
    {
        $query = Peminjaman::with(['user', 'detailPinjam.alat', 'pengembalian'])
            ->where('status', 'dikembalikan');

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('tgl_pinjam', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        $riwayat = $query->latest()->get();
        $tglMulai = $request->tgl_mulai;
        $tglSelesai = $request->tgl_selesai;

        // Generate PDF
        $pdf = Pdf::loadView('petugas.laporan.pdf', compact('riwayat', 'tglMulai', 'tglSelesai'))
                 ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Peminjaman-Alat.pdf');
    }
}
