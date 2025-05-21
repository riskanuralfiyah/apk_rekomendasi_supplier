<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanExcelExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths
{
    protected $laporans;
    protected $judul;
    protected $maxInfo;
    protected $jenisLaporan;
    protected $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    protected $startTableRow = 0;
    protected $periodeLabel = '-';

    public function __construct($laporans, $judul, $maxInfo, $jenisLaporan)
    {
        $this->laporans = $laporans;
        $this->judul = $judul;
        $this->maxInfo = $maxInfo;
        $this->jenisLaporan = $jenisLaporan;

        // Determine periode label based on jenis laporan
        if ($this->jenisLaporan === 'bulan') {
            // For bulan type, use the bulan and tahun from maxInfo
            if (isset($this->maxInfo['bulan']) && isset($this->maxInfo['tahun'])) {
                $bulan = $this->maxInfo['bulan'];
                $tahun = $this->maxInfo['tahun'];
                if ($bulan !== null && isset($this->namaBulan[$bulan])) {
                    $this->periodeLabel = $this->namaBulan[$bulan] . ' ' . $tahun;
                }
            }
        } elseif ($this->jenisLaporan !== 'bahan_baku') {
            // For other types (not bahan_baku), try to get periode from first item
            if ($this->laporans->count() > 0 && isset($this->laporans[0]->bulan) && isset($this->laporans[0]->tahun)) {
                $bulan = $this->laporans[0]->bulan;
                $tahun = $this->laporans[0]->tahun;
                if ($bulan !== null && isset($this->namaBulan[$bulan])) {
                    $this->periodeLabel = $this->namaBulan[$bulan] . ' ' . $tahun;
                }
            }
        }
    }

    public function collection()
    {
        $data = [];
    
        $data[] = [$this->judul];
        $data[] = ['Tanggal Export', now()->format('d F Y H:i')];
        $data[] = ['Total Data', count($this->laporans)];
    
        $info = [];
        if ($this->jenisLaporan === 'bahan_baku') {
            $info[] = ['Informasi:', ''];
            $info[] = ['Bahan Baku dengan Stok Masuk Terbanyak', $this->maxInfo['masuk'], number_format($this->maxInfo['nilai_masuk']) . ' ' . $this->maxInfo['satuan_masuk']];
            $info[] = ['Bahan Baku dengan Stok Keluar Terbanyak', $this->maxInfo['keluar'], number_format($this->maxInfo['nilai_keluar']) . ' ' . $this->maxInfo['satuan_keluar']];
        } elseif ($this->jenisLaporan === 'bulan') {
            $info[] = ['Informasi:', ''];
            $info[] = ['Bulan dengan Stok Masuk Terbanyak', $this->maxInfo['masuk'], number_format($this->maxInfo['nilai_masuk']) . ' ' . $this->maxInfo['satuan']];
            $info[] = ['Bulan dengan Stok Keluar Terbanyak', $this->maxInfo['keluar'], number_format($this->maxInfo['nilai_keluar']) . ' ' . $this->maxInfo['satuan']];
        } else {
            // $info[] = ['Periode', $this->periodeLabel];
        }
    
        $data = array_merge($data, $info);
    
        $data[] = [];
        $data[] = [];
    
        $this->startTableRow = count($data);
    
        $data[] = $this->headings();
    
        foreach ($this->laporans as $index => $item) {
            $rowData = [
                $index + 1,
                $item->bahanBaku->nama_bahan_baku ?? '-',
                $item->satuan ?? '-',
                number_format($item->stok_awal),
                number_format($item->total_stok_masuk),
                number_format($item->total_stok_keluar),
                number_format($item->sisa_stok),
            ];
            
            // Add periode column after No. (position 1) only for jenis laporan bukan 'bahan_baku'
            if ($this->jenisLaporan !== 'bahan_baku') {
                $itemPeriode = '-';
                if (isset($item->bulan) && isset($item->tahun) && isset($this->namaBulan[$item->bulan])) {
                    $itemPeriode = $this->namaBulan[$item->bulan] . ' ' . $item->tahun;
                }
                array_splice($rowData, 1, 0, [$itemPeriode]);
            }
            
            $data[] = $rowData;
        }
    
        return collect($data);
    }

    public function headings(): array
    {
        $headings = ['No.', 'Bahan Baku', 'Satuan', 'Stok Awal', 'Stok Masuk', 'Stok Keluar', 'Sisa Stok'];
        
        // Add 'Periode' column after 'No.' (position 1) only for jenis laporan bukan 'bahan_baku'
        if ($this->jenisLaporan !== 'bahan_baku') {
            array_splice($headings, 1, 0, ['Periode']);
        }
        
        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        $jumlahData = count($this->laporans);
        $hasTotal = $jumlahData > 0 ? 1 : 0;
        $totalRow = $this->startTableRow + $jumlahData + 1;
        $lastDataRow = $totalRow - $hasTotal;
        $lastColumn = $this->jenisLaporan !== 'bahan_baku' ? 'H' : 'G'; // H if has Periode column, G if not

        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle('A2:B3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '555555']]
        ]);

        $infoStartRow = 4;
        $infoEndRow = $this->startTableRow - 3;
        $sheet->getStyle("A{$infoStartRow}:C{$infoEndRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '4B49AC']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F7FF']],
        ]);

        $headerRow = $this->startTableRow;
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B49AC']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]],
        ]);

        $dataStartRow = $headerRow + 1;
        $sheet->getStyle("A{$dataStartRow}:{$lastColumn}{$lastDataRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        for ($i = $dataStartRow; $i <= $lastDataRow; $i++) {
            if ($i % 2 === 0) {
                $sheet->getStyle("A{$i}:{$lastColumn}{$i}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F8F9FA');
            }
        }

        if ($hasTotal) {
            $sheet->getStyle("A{$totalRow}:{$lastColumn}{$totalRow}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6C757D']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]]
            ]);
        }

        $numberColumns = $this->jenisLaporan !== 'bahan_baku' ? ['E', 'F', 'G', 'H'] : ['D', 'E', 'F', 'G'];
        foreach ($numberColumns as $col) {
            $sheet->getStyle("{$col}{$dataStartRow}:{$col}{$totalRow}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }

        return [];
    }

    public function title(): string
    {
        return 'Laporan Stok';
    }

    public function columnWidths(): array
    {
        if ($this->jenisLaporan !== 'bahan_baku') {
            return [
                'A' => 8,  // No.
                'B' => 15, // Periode
                'C' => 30, // Bahan Baku
                'D' => 15, // Satuan
                'E' => 15, // Stok Awal
                'F' => 15, // Stok Masuk
                'G' => 15, // Stok Keluar
                'H' => 15, // Sisa Stok
            ];
        }
        
        return [
            'A' => 8,  // No.
            'B' => 30, // Bahan Baku
            'C' => 15, // Satuan
            'D' => 15, // Stok Awal
            'E' => 15, // Stok Masuk
            'F' => 15, // Stok Keluar
            'G' => 15, // Sisa Stok
        ];
    }
}