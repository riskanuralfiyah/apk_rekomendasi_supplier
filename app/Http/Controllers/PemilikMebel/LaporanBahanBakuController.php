<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanBahanBakuController extends Controller
{
    private $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    public function index(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $searchTerm = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        $query = Laporan::with('bahanBaku');

        if ($bulan) {
            $query->where('bulan', $bulan);
        }

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        if (!empty($searchTerm)) {
            $query->where(function ($query) use ($searchTerm) {
                $query->whereHas('bahanBaku', function ($q) use ($searchTerm) {
                    $q->where('nama_bahan_baku', 'like', '%' . $searchTerm . '%')
                        ->orWhere('satuan', 'like', '%' . $searchTerm . '%');
                });

                if (is_numeric($searchTerm)) {
                    $query->orWhere('bulan', (int)$searchTerm);
                } else {
                    $bulanNamaKeAngka = [
                        'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
                        'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
                        'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12
                    ];
                    $searchLower = strtolower($searchTerm);
                    if (isset($bulanNamaKeAngka[$searchLower])) {
                        $query->orWhere('bulan', $bulanNamaKeAngka[$searchLower]);
                    }
                }

                $query->orWhere('tahun', 'like', '%' . $searchTerm . '%');
            });
        }

        $laporans = $query->orderBy('id_bahan_baku')
            ->paginate($perPage)
            ->appends([
                'bulan' => $bulan,
                'tahun' => $tahun,
                'search' => $searchTerm,
                'per_page' => $perPage
            ]);

        return view('pages.PemilikMebel.LaporanBahanBaku.index', [
            'laporans' => $laporans,
            'currentBulan' => $bulan,
            'currentTahun' => $tahun,
            'namaBulan' => $this->namaBulan,
            'searchTerm' => $searchTerm,
        ]);
    }

    public function exportToPdf(Request $request)
    {
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));

        $laporans = Laporan::with('bahanBaku')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderBy('id_bahan_baku')
            ->get();

        // Cari bahan baku dengan stok masuk terbanyak
        $maxStokMasuk = $laporans->sortByDesc('total_stok_masuk')->first();
        
        // Cari bahan baku dengan stok keluar terbanyak
        $maxStokKeluar = $laporans->sortByDesc('total_stok_keluar')->first();

        $pdf = Pdf::loadView('pages.PemilikMebel.LaporanBahanBaku.pdf', [
            'laporans' => $laporans,
            'currentBulan' => $bulan,
            'currentTahun' => $tahun,
            'namaBulan' => $this->namaBulan,
            'maxStokMasuk' => $maxStokMasuk,
            'maxStokKeluar' => $maxStokKeluar
        ]);

        return $pdf->download("laporan_bahan_baku_{$this->namaBulan[$bulan]}_{$tahun}.pdf");
    }
}