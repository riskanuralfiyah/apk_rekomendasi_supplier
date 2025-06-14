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
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\LaporanBahanBakuService;


class LaporanBahanBakuController extends Controller
{
    private $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    public function index(Request $request, LaporanBahanBakuService $service)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $bulan = (int) $request->input('bulan', $currentMonth);
        $tahun = (int) $request->input('tahun', $currentYear);
        $idBahanBaku = $request->input('id_bahan_baku', null);
        $searchTerm = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        // Get raw data from service
        $laporanData = $service->getLaporan($bulan, $tahun, $idBahanBaku);

        // Apply search filter if exists
        if (!empty($searchTerm)) {
            $laporanData = $laporanData->filter(function ($item) use ($searchTerm) {
                return stripos($item['nama_bahan_baku'], $searchTerm) !== false 
                    || stripos($item['ukuran'], $searchTerm) !== false;
            });
        }

        // Paginate the results
        $page = $request->input('page', 1);
        $items = $laporanData->forPage($page, $perPage);
        $laporanPaginated = new LengthAwarePaginator(
            $items,
            $laporanData->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get bahan bakus for dropdown
        $bahanBakus = BahanBaku::query()
            ->when(!empty($searchTerm), function ($query) use ($searchTerm) {
                $query->where('nama_bahan_baku', 'like', "%$searchTerm%")
                      ->orWhere('ukuran', 'like', "%$searchTerm%");
            })
            ->orderBy('nama_bahan_baku')
            ->paginate($perPage);

        return view('pages.PemilikMebel.LaporanBahanBaku.index', [
            'laporans' => $laporanPaginated,
            'bahanBakus' => $bahanBakus,
            'currentBulan' => $bulan,
            'currentTahun' => $tahun,
            'searchTerm' => $searchTerm,
            'namaBulan' => $this->namaBulan,
            'daftarBahanBaku' => BahanBaku::orderBy('nama_bahan_baku')->get(),
            'idBahanBaku' => $idBahanBaku,
        ]);
    }

    public function exportToPdf(Request $request, LaporanBahanBakuService $service)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
    
        $bulan = (int) $request->input('bulan', $currentMonth);
        $tahun = (int) $request->input('tahun', $currentYear);
        $idBahanBaku = $request->input('id_bahan_baku', null);
        $searchTerm = $request->input('search', '');
    
        // Get raw data from service with BOTH bulan and bahan baku filters
        $laporanData = $service->getLaporan($bulan, $tahun, $idBahanBaku);
    
        // Apply search filter if exists
        if (!empty($searchTerm)) {
            $laporanData = $laporanData->filter(function ($item) use ($searchTerm) {
                return stripos($item['nama_bahan_baku'], $searchTerm) !== false 
                    || stripos($item['ukuran'], $searchTerm) !== false;
            });
        }
    
        // Get bahan baku name if filtered
        $bahanBakuNama = '';
        if ($idBahanBaku) {
            $bahanBaku = BahanBaku::find($idBahanBaku);
            $bahanBakuNama = $bahanBaku->nama_bahan_baku ?? '';
        }
    
        // Determine report type and prepare max info
        $jenisLaporan = 'full';
        $maxInfo = [
            'masuk' => '-',
            'keluar' => '-',
            'awal' => '-',
            'sisa' => '-',
            'nilai_masuk' => 0,
            'nilai_keluar' => 0,
            'nilai_awal' => 0,
            'nilai_sisa' => 0,
            'ukuran' => '',
            'ukuran_masuk' => '',
            'ukuran_keluar' => '',
            'bulan' => $bulan,
            'tahun' => $tahun
        ];
    
        // Update logic for report type determination
        if ($idBahanBaku) {
            $jenisLaporan = $bulan ? 'bulan_dan_bahan' : 'bulan';
            
            if ($laporanData->isNotEmpty()) {
                $maxInfo = [
                    'masuk' => $this->getMaxInfo($laporanData, 'bulan'),
                    'keluar' => $this->getMaxInfo($laporanData, 'bulan'),
                    'awal' => $this->getMaxInfo($laporanData, 'stok_awal', 'bulan'),
                    'sisa' => $this->getMaxInfo($laporanData, 'sisa_stok', 'bulan'),
                    'nilai_masuk' => $laporanData->max('stok_masuk'),
                    'nilai_keluar' => $laporanData->max('stok_keluar'),
                    'nilai_awal' => $laporanData->max('stok_awal'),
                    'nilai_sisa' => $laporanData->max('sisa_stok'),
                    'ukuran' => $laporanData->first()['ukuran'] ?? '',
                    'bulan' => $bulan,
                    'tahun' => $tahun
                ];
            }
        } elseif ($bulan) {
            $jenisLaporan = 'bahan_baku';
            if ($laporanData->isNotEmpty()) {
                $maxInfo = [
                    'masuk' => $this->getMaxInfo($laporanData,'nama_bahan_baku'),
                    'keluar' => $this->getMaxInfo($laporanData, 'nama_bahan_baku'),
                    'awal' => $this->getMaxInfo($laporanData, 'stok_awal', 'nama_bahan_baku'),
                    'sisa' => $this->getMaxInfo($laporanData, 'sisa_stok', 'nama_bahan_baku'),
                    'nilai_masuk' => $laporanData->max('stok_masuk'),
                    'nilai_keluar' => $laporanData->max('stok_keluar'),
                    'nilai_awal' => $laporanData->max('stok_awal'),
                    'nilai_sisa' => $laporanData->max('sisa_stok'),
                    'ukuran_masuk' => $laporanData->first()['ukuran'] ?? '',
                    'ukuran_keluar' => $laporanData->first()['ukuran'] ?? '',
                    'bulan' => $bulan,
                    'tahun' => $tahun
                ];
            }
        }
    
        // Generate PDF
        $pdf = PDF::loadView('pages.PemilikMebel.LaporanBahanBaku.pdf', [
            'currentBulan' => $bulan,
            'currentTahun' => $tahun,
            'laporans' => $laporanData,
            'namaBulan' => $this->namaBulan,
            'jenisLaporan' => $jenisLaporan,
            'maxInfo' => $maxInfo,
            'bahanBakuNama' => $bahanBakuNama,
            'searchTerm' => $searchTerm
        ]);
    
        // Generate filename
        $filename = "laporan_bahan_baku";
        if ($bulan) $filename .= "_" . ($this->namaBulan[$bulan] ?? $bulan);
        if ($tahun) $filename .= "_" . $tahun;
        if ($idBahanBaku) $filename .= "_" . str_replace(' ', '_', strtolower($bahanBakuNama));
        if ($searchTerm) $filename .= "_search_" . str_replace(' ', '_', strtolower($searchTerm));
    
        return $pdf->download($filename . ".pdf");
    }

    private function getMaxInfo(Collection $data, string $field, string $groupBy = ''): string
    {
        $maxItem = $data->sortByDesc($field)->first();
        return $maxItem ? ($groupBy ? "{$maxItem[$groupBy]} ({$maxItem[$field]})" : "{$maxItem[$field]}") : '-';
    }    
    

    public function exportToExcel(Request $request, LaporanBahanBakuService $service)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
    
        $bulan = (int) $request->input('bulan', $currentMonth);
        $tahun = (int) $request->input('tahun', $currentYear);
        $idBahanBaku = $request->input('id_bahan_baku', null);
        $searchTerm = $request->input('search', '');
    
        // Get raw data from service with BOTH bulan and bahan baku filters
        $laporanData = $service->getLaporan($bulan, $tahun, $idBahanBaku);
    
        // Apply search filter if exists
        if (!empty($searchTerm)) {
            $laporanData = $laporanData->filter(function ($item) use ($searchTerm) {
                return stripos($item['nama_bahan_baku'], $searchTerm) !== false 
                    || stripos($item['ukuran'], $searchTerm) !== false;
            });
        }
    
        // Get bahan baku name if filtered
        $bahanBakuNama = '';
        if ($idBahanBaku) {
            $bahanBaku = BahanBaku::find($idBahanBaku);
            $bahanBakuNama = $bahanBaku->nama_bahan_baku ?? '';
        }
    
        // Generate judul file
        $judul = "Laporan Stok Bahan Baku";
        if ($bulan) $judul .= " Bulan " . ($this->namaBulan[$bulan] ?? $bulan);
        if ($tahun) $judul .= " Tahun " . $tahun;
        if ($idBahanBaku) {
            $judul .= " - " . $bahanBakuNama;
        }
    
        // Determine report type and prepare max info
        $jenisLaporan = 'full';
        $maxInfo = [
            'masuk' => '-',
            'keluar' => '-',
            'awal' => '-',
            'sisa' => '-',
            'nilai_masuk' => 0,
            'nilai_keluar' => 0,
            'nilai_awal' => 0,
            'nilai_sisa' => 0,
            'ukuran' => '',
            'ukuran_masuk' => '',
            'ukuran_keluar' => '',
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nama_bahan_masuk' => '',
            'nama_bahan_keluar' => ''
        ];
    
        // Update logic for report type determination
        if ($idBahanBaku) {
            $jenisLaporan = $bulan ? 'bulan_dan_bahan' : 'bulan';
            
            if ($laporanData->isNotEmpty()) {
                $maxInfo = [
                    'masuk' => $this->getMaxInfo($laporanData, 'stok_masuk', 'bulan'),
                    'keluar' => $this->getMaxInfo($laporanData, 'stok_keluar', 'bulan'),
                    'awal' => $this->getMaxInfo($laporanData, 'stok_awal', 'bulan'),
                    'sisa' => $this->getMaxInfo($laporanData, 'sisa_stok', 'bulan'),
                    'nilai_masuk' => $laporanData->max('stok_masuk'),
                    'nilai_keluar' => $laporanData->max('stok_keluar'),
                    'nilai_awal' => $laporanData->max('stok_awal'),
                    'nilai_sisa' => $laporanData->max('sisa_stok'),
                    'ukuran' => $laporanData->first()['ukuran'] ?? '',
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nama_bahan_masuk' => $this->getMaxBahanBaku($laporanData, 'stok_masuk'),
                    'nama_bahan_keluar' => $this->getMaxBahanBaku($laporanData, 'stok_keluar')
                ];
            }
        } elseif ($bulan) {
            $jenisLaporan = 'bahan_baku';
            if ($laporanData->isNotEmpty()) {
                $maxInfo = [
                    'masuk' => $this->getMaxInfo($laporanData, 'stok_masuk', 'nama_bahan_baku'),
                    'keluar' => $this->getMaxInfo($laporanData, 'stok_keluar', 'nama_bahan_baku'),
                    'awal' => $this->getMaxInfo($laporanData, 'stok_awal', 'nama_bahan_baku'),
                    'sisa' => $this->getMaxInfo($laporanData, 'sisa_stok', 'nama_bahan_baku'),
                    'nilai_masuk' => $laporanData->max('stok_masuk'),
                    'nilai_keluar' => $laporanData->max('stok_keluar'),
                    'nilai_awal' => $laporanData->max('stok_awal'),
                    'nilai_sisa' => $laporanData->max('sisa_stok'),
                    'ukuran_masuk' => $laporanData->first()['ukuran'] ?? '',
                    'ukuran_keluar' => $laporanData->first()['ukuran'] ?? '',
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nama_bahan_masuk' => $this->getMaxBahanBaku($laporanData, 'stok_masuk'),
                    'nama_bahan_keluar' => $this->getMaxBahanBaku($laporanData, 'stok_keluar')
                ];
            }
        }
    
        // Generate filename
        $filename = "laporan_bahan_baku";
        if ($bulan) $filename .= "_" . ($this->namaBulan[$bulan] ?? $bulan);
        if ($tahun) $filename .= "_" . $tahun;
        if ($idBahanBaku) $filename .= "_" . str_replace(' ', '_', strtolower($bahanBakuNama));
        if ($searchTerm) $filename .= "_search_" . str_replace(' ', '_', strtolower($searchTerm));
    
        return Excel::download(
            new LaporanExcelExport($laporanData, $judul, $maxInfo, $jenisLaporan, $this->namaBulan),
            $filename . '.xlsx'
        );
    }
    
    protected function getMaxBahanBaku($laporanData, $stokType)
    {
        $maxItem = $laporanData->sortByDesc($stokType)->first();
        return $maxItem['nama_bahan_baku'] ?? null; // Return the name of the raw material
    }
    

}