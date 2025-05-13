<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanBahanBakuController extends Controller
{
    /**
     * Menampilkan daftar laporan stok bahan baku
     */
    public function index(Request $request)
{
    $laporans = Laporan::when($request->bulan, function ($query) use ($request) {
            return $query->whereMonth('bulan', $request->bulan);
        })
        ->when($request->tahun, function ($query) use ($request) {
            return $query->whereYear('bulan', $request->tahun); // <- ini diubah
        })
        ->when($request->id_bahan_baku, function ($query) use ($request) {
            return $query->where('id_bahan_baku', $request->id_bahan_baku);
        })
        ->with('bahanBaku')
        ->paginate(10);

    $bahanBaku = BahanBaku::all();

    return view('pages.PemilikMebel.LaporanBahanBaku.index', compact('laporans', 'bahanBaku'));
}


    /**
     * Menyimpan laporan bahan baku baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'bulan' => 'required|date',
            'tahun' => 'required|integer',
            'id_bahan_baku' => 'required|exists:bahan_bakus,id',
            'satuan' => 'required|string',
            'total_stok_masuk' => 'required|integer',
            'total_stok_keluar' => 'required|integer',
        ]);

        // Mendapatkan stok awal bulan sebelumnya
        $stok_awal = $this->getStokAwal($request->id_bahan_baku, $request->bulan, $request->tahun);

        // Membuat laporan baru
        Laporan::create([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'id_bahan_baku' => $request->id_bahan_baku,
            'satuan' => $request->satuan,
            'stok_awal' => $stok_awal,
            'total_stok_masuk' => $request->total_stok_masuk,
            'total_stok_keluar' => $request->total_stok_keluar,
            'sisa_stok' => $stok_awal + $request->total_stok_masuk - $request->total_stok_keluar,
        ]);

        return redirect()->route('laporanbahanbaku.pemilikmebel')->with('success', 'Laporan berhasil disimpan');
    }

    /**
     * Mendapatkan stok awal dari bulan sebelumnya
     */
    private function getStokAwal($id_bahan_baku, $bulan, $tahun)
    {
        // Mengambil laporan stok bahan baku dari bulan sebelumnya
        $previousMonth = Carbon::createFromFormat('Y-m', "$tahun-$bulan")->subMonth()->format('m');
        $previousYear = Carbon::createFromFormat('Y-m', "$tahun-$bulan")->subMonth()->format('Y');

        $previousReport = Laporan::where('id_bahan_baku', $id_bahan_baku)
            ->whereMonth('bulan', $previousMonth)
            ->whereYear('tahun', $previousYear)
            ->orderBy('bulan', 'desc')
            ->first();

        // Jika ada laporan stok sebelumnya, maka stok awal adalah stok sisa bulan sebelumnya
        return $previousReport ? $previousReport->sisa_stok : 0;
    }

    /**
     * Menampilkan laporan berdasarkan bulan dan tahun
     */
    public function show($bulan, $tahun)
    {
        // Menampilkan laporan berdasarkan bulan dan tahun
        $laporan = Laporan::whereMonth('bulan', $bulan)
            ->whereYear('tahun', $tahun)
            ->with('bahanBaku')
            ->get();

        return view('pages.PemilikMebel.LaporanBahanBaku.show', compact('laporan', 'bulan', 'tahun'));
    }
}
