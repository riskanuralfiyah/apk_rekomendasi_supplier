<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\HasilRekomendasi;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratPemesananController extends Controller
{
    public function index()
    {
        // ambil user yg login
        $user = auth()->user();
    
        // ambil data bahan dan supplier sama untuk kedua role
        $bahanHampirHabis = BahanBaku::whereColumn('jumlah_stok', '<=', 'stok_minimum')->get();
        $bahanTambahan = BahanBaku::whereColumn('jumlah_stok', '>', 'stok_minimum')->get();
        $supplierRekomendasi = HasilRekomendasi::where('peringkat', 1)
            ->with('supplier')
            ->orderBy('peringkat')
            ->get();
        $supplierAlternatif = HasilRekomendasi::where('peringkat', '>', 1)
            ->with('supplier')
            ->orderBy('peringkat')
            ->get();
    
        // cek role user, return view berbeda
        if ($user->role == 'pemilikmebel') {
            return view('pages.PemilikMebel.SuratPemesanan.index', compact(
                'bahanHampirHabis',
                'bahanTambahan',
                'supplierRekomendasi',
                'supplierAlternatif'
            ));
        } elseif ($user->role == 'karyawan') {
            return view('pages.Karyawan.SuratPemesanan.index', compact(
                'bahanHampirHabis',
                'bahanTambahan',
                'supplierRekomendasi',
                'supplierAlternatif'
            ));
        } else {
            abort(403, 'Unauthorized access');
        }
    }
    

    public function buatSurat(Request $request)
    {
        $user = auth()->user();
    
        $bahanDipilih = $request->input('bahan_baku', []);
        $jumlahBahan = $request->input('jumlah', []);
        $supplierId = $request->input('supplier');

         // validasi: jika tidak ada bahan baku atau supplier yang dipilih
        if (empty($bahanDipilih) || !$supplierId) {
            return back()->with('error', 'Silakan pilih minimal satu bahan baku dan supplier terlebih dahulu.');
        }
    
        // ambil data bahan baku
        $bahanList = BahanBaku::whereIn('id', $bahanDipilih)->get();
    
        // tambahkan jumlah ke tiap bahan
        foreach ($bahanList as $bahan) {
            $bahan->jumlah = $jumlahBahan[$bahan->id] ?? 0;
        }
    
        // ambil data supplier
        $supplier = Supplier::findOrFail($supplierId);
    
        // tentukan view pdf sesuai role
        if ($user->role == 'pemilikmebel') {
            $view = 'pages.PemilikMebel.SuratPemesanan.pdf';
        } elseif ($user->role == 'karyawan') {
            $view = 'pages.Karyawan.SuratPemesanan.pdf';
        } else {
            abort(403, 'Unauthorized');
        }
    
        // generate pdf
        $pdf = PDF::loadView($view, [
            'bahanList' => $bahanList,
            'supplier' => $supplier,
        ]);
    
        return $pdf->download('surat-pemesanan.pdf');
    }
    
}
