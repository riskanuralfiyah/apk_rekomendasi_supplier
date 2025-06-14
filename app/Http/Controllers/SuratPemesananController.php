<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\HasilRekomendasi;
use App\Models\Supplier;
use App\Models\SuratPemesanan;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratPemesananController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $bahanHampirHabis = BahanBaku::whereColumn('jumlah_stok', '<=', 'stok_minimum')->get();
        $bahanTambahan = BahanBaku::whereColumn('jumlah_stok', '>', 'stok_minimum')->get();
        $supplierRekomendasi = HasilRekomendasi::where('peringkat', 1)->with('supplier')->get();
        $supplierAlternatif = HasilRekomendasi::where('peringkat', '>', 1)->with('supplier')->get();

        $view = match ($user->role) {
            'pemilikmebel' => 'pages.PemilikMebel.SuratPemesanan.index',
            'karyawan' => 'pages.Karyawan.SuratPemesanan.index',
            default => abort(403, 'Unauthorized access'),
        };

        return view($view, compact(
            'bahanHampirHabis',
            'bahanTambahan',
            'supplierRekomendasi',
            'supplierAlternatif'
        ));
    }

    public function buatSurat(Request $request)
    {
        $user = auth()->user();

        $bahanDipilih = $request->input('bahan_baku', []);
        $jumlahBahan = $request->input('jumlah', []);
        $satuanBahan = $request->input('satuan', []);
        $supplierId = $request->input('supplier');

        if (empty($bahanDipilih) || !$supplierId) {
            return back()->with('error', 'Silakan pilih minimal satu bahan baku dan supplier terlebih dahulu.');
        }

        $bahanList = BahanBaku::whereIn('id', $bahanDipilih)->get();
        foreach ($bahanList as $bahan) {
            $bahan->jumlah = $jumlahBahan[$bahan->id] ?? 0;
            $bahan->satuan = $satuanBahan[$bahan->id] ?? '-';
        }

        $supplier = Supplier::findOrFail($supplierId);

        // === Generate Nomor Surat ===
        $bulan = now()->format('m');
        $tahun = now()->format('Y');

        $lastSurat = SuratPemesanan::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastSurat) {
            preg_match('/^(\d+)/', $lastSurat->nomor_surat, $matches);
            $lastNumber = isset($matches[1]) ? (int)$matches[1] : 0;
        }

        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $nomorSurat = "{$newNumber}/SPB/RM/{$bulan}/{$tahun}";

        // === Simpan ke database ===
        $surat = new SuratPemesanan();
        $surat->nomor_surat = $nomorSurat;
        $surat->id_supplier = $supplierId;
        $surat->save();

        // === Generate PDF ===
        $view = match ($user->role) {
            'pemilikmebel' => 'pages.PemilikMebel.SuratPemesanan.pdf',
            'karyawan' => 'pages.Karyawan.SuratPemesanan.pdf',
            default => abort(403, 'Unauthorized'),
        };

        $pdf = PDF::loadView($view, [
            'bahanList' => $bahanList,
            'supplier' => $supplier,
            'nomorSurat' => $nomorSurat,
        ]);

        return $pdf->download('surat-pemesanan.pdf');
    }
}
