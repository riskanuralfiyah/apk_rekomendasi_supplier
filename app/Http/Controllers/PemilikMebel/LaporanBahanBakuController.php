<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use App\Exports\LaporanExcelExport; // supaya bisa dipanggil saat export Excel
use App\Models\Laporan;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Carbon\Carbon;


class LaporanBahanBakuController extends Controller
{
    private $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    public function index(Request $request)
    {
        // ambil bulan dan tahun dari request, jika tidak ada pakai sekarang
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);
        $searchTerm = $request->input('search', '');
        $perPage = $request->input('per_page', 10);
        $bahanBakuId = $request->input('id_bahan_baku');
    
        $query = Laporan::with('bahanBaku');
    
        if ($bulan) {
            $query->where('bulan', $bulan);
        }
    
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
    
        if ($bahanBakuId) {
            $query->where('id_bahan_baku', $bahanBakuId);
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
                'id_bahan_baku' => $bahanBakuId,
                'search' => $searchTerm,
                'per_page' => $perPage
            ]);
    
        return view('pages.PemilikMebel.LaporanBahanBaku.index', [
            'laporans' => $laporans,
            'currentBulan' => $bulan,
            'currentTahun' => $tahun,
            'currentBahanBakuId' => $bahanBakuId,
            'namaBulan' => $this->namaBulan,
            'searchTerm' => $searchTerm,
            'daftarBahanBaku' => BahanBaku::orderBy('nama_bahan_baku')->get(),
        ]);
    }

    public function exportToPdf(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));
        $bahanBakuId = $request->input('id_bahan_baku');
    
        $query = Laporan::with('bahanBaku');
    
        if ($bulan) {
            $query->where('bulan', $bulan);
        }
    
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
    
        if ($bahanBakuId) {
            $query->where('id_bahan_baku', $bahanBakuId);
        }
    
        $laporans = $query->orderBy('id_bahan_baku')->get();
    
        // Determine jenis laporan and max info
        $jenisLaporan = '';
        $maxInfo = [
            'masuk' => '-',
            'keluar' => '-',
            'nilai_masuk' => 0,
            'nilai_keluar' => 0,
            'satuan' => '',
            'satuan_masuk' => '',
            'satuan_keluar' => '',
            'bulan' => $bulan,
            'tahun' => $tahun
        ];
        
        if ($bahanBakuId && !$bulan) {
            $jenisLaporan = 'bulan';
            
            if ($laporans->isNotEmpty()) {
                $maxStokMasuk = $laporans->sortByDesc('total_stok_masuk')->first();
                $maxStokKeluar = $laporans->sortByDesc('total_stok_keluar')->first();
                
                $maxInfo = [
                    'masuk' => $maxStokMasuk ? $this->namaBulan[$maxStokMasuk->bulan] ?? '-' : '-',
                    'keluar' => $maxStokKeluar ? $this->namaBulan[$maxStokKeluar->bulan] ?? '-' : '-',
                    'nilai_masuk' => $maxStokMasuk->total_stok_masuk ?? 0,
                    'nilai_keluar' => $maxStokKeluar->total_stok_keluar ?? 0,
                    'satuan' => $maxStokMasuk->satuan ?? '',
                    'bulan' => $bulan,
                    'tahun' => $tahun
                ];
            }
        } elseif (!$bahanBakuId && $bulan) {
            $jenisLaporan = 'bahan_baku';
            
            if ($laporans->isNotEmpty()) {
                $maxStokMasuk = $laporans->sortByDesc('total_stok_masuk')->first();
                $maxStokKeluar = $laporans->sortByDesc('total_stok_keluar')->first();
                
                $maxInfo = [
                    'masuk' => $maxStokMasuk->bahanBaku->nama_bahan_baku ?? '-',
                    'keluar' => $maxStokKeluar->bahanBaku->nama_bahan_baku ?? '-',
                    'nilai_masuk' => $maxStokMasuk->total_stok_masuk ?? 0,
                    'nilai_keluar' => $maxStokKeluar->total_stok_keluar ?? 0,
                    'satuan_masuk' => $maxStokMasuk->satuan ?? '',
                    'satuan_keluar' => $maxStokKeluar->satuan ?? '',
                    'bulan' => $bulan,
                    'tahun' => $tahun
                ];
            }
        } else {
            $jenisLaporan = 'full';
        }
    
        $pdf = Pdf::loadView('pages.PemilikMebel.LaporanBahanBaku.pdf', [
            'currentBulan' => $bulan,
            'currentTahun' => $tahun,
            'laporans' => $laporans,
            'namaBulan' => $this->namaBulan,
            'jenisLaporan' => $jenisLaporan,
            'maxInfo' => $maxInfo
        ]);
    
        $filename = "laporan_bahan_baku";
        if ($bulan) $filename .= "_".($this->namaBulan[$bulan] ?? $bulan);
        if ($tahun) $filename .= "_".$tahun;
        if ($bahanBakuId) {
            $bahanBaku = BahanBaku::find($bahanBakuId);
            $filename .= "_".($bahanBaku->nama_bahan_baku ?? '');
        }
        
        return $pdf->download($filename.".pdf");
    }

    public function exportToExcel(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));
        $bahanBakuId = $request->input('id_bahan_baku');

        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
    
        // Query data (sama seperti PDF)
        $query = Laporan::with('bahanBaku');
    
        if ($bulan) {
            $query->where('bulan', $bulan);
        }
    
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
    
        if ($bahanBakuId) {
            $query->where('id_bahan_baku', $bahanBakuId);
        }
    
        $laporans = $query->orderBy('id_bahan_baku')->get();
        
        // Generate judul file
        $judul = "Laporan Stok Bahan Baku";
        if ($bulan) $judul .= " Bulan ".($this->namaBulan[$bulan] ?? $bulan);
        if ($tahun) $judul .= " Tahun ".$tahun;
        if ($bahanBakuId) {
            $bahanBaku = BahanBaku::find($bahanBakuId);
            $judul .= " - ".($bahanBaku->nama_bahan_baku ?? '');
        }
        
        // Generate nama file
        $filename = strtolower(str_replace(' ', '_', $judul));
        
        // Logika maxInfo (sama seperti PDF)
        $jenisLaporan = '';
        $maxInfo = [
            'masuk' => '-',
            'keluar' => '-',
            'nilai_masuk' => 0,
            'nilai_keluar' => 0,
            'satuan' => '',
            'satuan_masuk' => '',
            'satuan_keluar' => ''
        ];
        
        if ($bahanBakuId && !$bulan) {
            // Filter bahan baku + tahun (tampilkan bulan dengan stok terbanyak)
            $jenisLaporan = 'bulan';
            
            if ($laporans->isNotEmpty()) {
                $maxStokMasuk = $laporans->sortByDesc('total_stok_masuk')->first();
                $maxStokKeluar = $laporans->sortByDesc('total_stok_keluar')->first();
                
                $maxInfo = [
                    'masuk' => $maxStokMasuk ? $this->namaBulan[$maxStokMasuk->bulan] ?? '-' : '-',
                    'keluar' => $maxStokKeluar ? $this->namaBulan[$maxStokKeluar->bulan] ?? '-' : '-',
                    'nilai_masuk' => $maxStokMasuk->total_stok_masuk ?? 0,
                    'nilai_keluar' => $maxStokKeluar->total_stok_keluar ?? 0,
                    'satuan' => $maxStokMasuk->satuan ?? '',
                ];
            }
        } elseif (!$bahanBakuId && $bulan) {
            // Filter bulan + tahun (tampilkan bahan baku dengan stok terbanyak)
            $jenisLaporan = 'bahan_baku';
            
            if ($laporans->isNotEmpty()) {
                $maxStokMasuk = $laporans->sortByDesc('total_stok_masuk')->first();
                $maxStokKeluar = $laporans->sortByDesc('total_stok_keluar')->first();
                
                $maxInfo = [
                    'masuk' => $maxStokMasuk->bahanBaku->nama_bahan_baku ?? '-',
                    'keluar' => $maxStokKeluar->bahanBaku->nama_bahan_baku ?? '-',
                    'nilai_masuk' => $maxStokMasuk->total_stok_masuk ?? 0,
                    'nilai_keluar' => $maxStokKeluar->total_stok_keluar ?? 0,
                    'satuan_masuk' => $maxStokMasuk->satuan ?? '',
                    'satuan_keluar' => $maxStokKeluar->satuan ?? '',
                ];
            }
        }
        
        return Excel::download(
            new LaporanExcelExport($laporans, $judul, $maxInfo, $jenisLaporan),
            $filename.'.xlsx'
        );
    }

}
