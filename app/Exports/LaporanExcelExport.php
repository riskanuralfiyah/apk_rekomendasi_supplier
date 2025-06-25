<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanExcelExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithTitle, WithCustomStartCell
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
    protected $periodeLabel = '-';
    protected $startTableRow = 5;
    // Fungsi helper untuk mendapatkan nama bulan
    protected function getNamaBulan($bulan) {
        return $this->namaBulan[$bulan] ?? 'Bulan ' . $bulan;
    }

    public function __construct($laporans, $judul, $maxInfo, $jenisLaporan)
    {
        $this->laporans = $laporans;
        $this->judul = $judul;
        $this->maxInfo = $maxInfo;
        $this->jenisLaporan = $jenisLaporan;

        if ($this->jenisLaporan === 'bulan' && $laporans->isNotEmpty()) {
            $maxStokMasuk = $laporans->sortByDesc('total_stok_masuk')->first();
            $maxStokKeluar = $laporans->sortByDesc('total_stok_keluar')->first();
            
            $this->maxInfo = [
                'masuk' => $this->namaBulan[$maxStokMasuk->bulan] ?? 'Bulan ' . $maxStokMasuk->bulan,
                'keluar' => $this->namaBulan[$maxStokKeluar->bulan] ?? 'Bulan ' . $maxStokKeluar->bulan,
                'tahun_masuk' => $maxStokMasuk->tahun ?? '',
                'tahun_keluar' => $maxStokKeluar->tahun ?? '',
            ];
        } elseif ($this->jenisLaporan !== 'bahan_baku') {
            if ($this->laporans->count() > 0 && isset($this->laporans[0]->bulan) && isset($this->laporans[0]->tahun)) { // Fixed missing parenthesis
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
        return $this->laporans;
    }

    public function headings(): array
    {
        $headings = ['No.', 'Bahan Baku', 'Ukuran', 'Stok Awal', 'Stok Masuk', 'Stok Keluar', 'Sisa Stok'];
        if ($this->jenisLaporan !== 'bahan_baku') {
            array_splice($headings, 1, 0, ['Periode']);
        }
        return $headings;
    }

    public function map($item): array
    {
        static $index = 0;
        $index++;
    
        $rowData = [
            $index,
            $item['nama_bahan_baku'] ?? '-', // Accessing as an array
            $item['ukuran'] ?? '-', // Accessing as an array
            number_format($item['stok_awal']), // Accessing as an array
            number_format($item['stok_masuk']), // Accessing as an array
            number_format($item['stok_keluar']), // Accessing as an array
            number_format($item['sisa_stok']), // Accessing as an array
        ];
    
        if ($this->jenisLaporan !== 'bahan_baku') {
            $itemPeriode = '-';
            if (isset($item['bulan']) && isset($item['tahun']) && isset($this->namaBulan[$item['bulan']])) {
                $itemPeriode = $this->namaBulan[$item['bulan']] . ' ' . $item['tahun'];
            }
            array_splice($rowData, 1, 0, [$itemPeriode]);
        }
    
        return $rowData;
    }
    

    public function title(): string
    {
        return 'Laporan Stok Bahan Baku';
    }

    public function startCell(): string
    {
        return 'A' . $this->startTableRow;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = $this->jenisLaporan !== 'bahan_baku' ? 'H' : 'G';
                $lastDataRow = $this->startTableRow + count($this->laporans);
    
                // Set header information
                $sheet->setCellValue('A1', $this->judul);
                $sheet->mergeCells("A1:{$lastColumn}1");
    
                // Add a blank row after the title
                $sheet->setCellValue('A2', ''); // This creates a blank row
                $sheet->getRowDimension(2)->setRowHeight(20); // Optional: Set row height for the blank row
    
                $sheet->setCellValue('A3', 'Tanggal Export');
                $sheet->mergeCells('A3:B3');
                \Carbon\Carbon::setLocale('id'); // set ke locale Indonesia
                $sheet->setCellValue('C3', now()->translatedFormat('d F Y H:i'));
                $sheet->mergeCells('C3:D3');
                
                // $sheet->setCellValue('A4', 'Total Data');
                // $sheet->mergeCells('A4:B4'); // merge cells for total data label
                // $sheet->setCellValue('C4', count($this->laporans));
    
                // Set horizontal alignment of the total data value to left
                $sheet->getStyle('C4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    
                // Set additional info
                $infoStartRow = $lastDataRow + 2; // Adjust this if needed
                // $sheet->setCellValue("A{$infoStartRow}", 'Informasi Tambahan:');
                
                if ($this->jenisLaporan === 'bahan_baku') {
                    $sheet->setCellValue("A{$infoStartRow}", 'Informasi Tambahan:');
                    
                    // Bahan Baku dengan Stok Masuk Terbanyak
                    $sheet->setCellValue("A" . ($infoStartRow + 1), 'Bahan Baku dengan Stok Masuk Terbanyak:');
                    $sheet->mergeCells("A" . ($infoStartRow + 1) . ":C" . ($infoStartRow + 1)); // Merge cells for label
                    $sheet->setCellValue("C" . ($infoStartRow + 1), $this->maxInfo['masuk']); // Keep value in C
                    $sheet->setCellValue("D" . ($infoStartRow + 1),
                    ($this->maxInfo['nama_bahan_masuk'] ?? '-') . ' ' .
                    ($this->maxInfo['ukuran_masuk'] ?? '')
                );
                    $sheet->setCellValue("E" . ($infoStartRow + 1), ' (' .number_format($this->maxInfo['nilai_masuk']). ' batang)' ); // Keep value in D
                
                    // Bahan Baku dengan Stok Keluar Terbanyak
                    $sheet->setCellValue("A" . ($infoStartRow + 2), 'Bahan Baku dengan Stok Keluar Terbanyak:');
                    $sheet->mergeCells("A" . ($infoStartRow + 2) . ":C" . ($infoStartRow + 2)); // Merge cells for label
                    $sheet->setCellValue("C" . ($infoStartRow + 2), $this->maxInfo['keluar']); // Keep value in C
                    $sheet->setCellValue("D" . ($infoStartRow + 2),
                        ($this->maxInfo['nama_bahan_keluar'] ?? '-') . ' ' .
                        ($this->maxInfo['ukuran_keluar'] ?? '')
                    );
                    $sheet->setCellValue("E" . ($infoStartRow + 2), ' (' .number_format($this->maxInfo['nilai_keluar']) . ' batang)'); // Keep value in D
                
                } elseif ($this->jenisLaporan === 'bulan') {
                    // Set label for "Bulan dengan Stok Masuk Terbanyak"
                    $sheet->setCellValue("A{$infoStartRow}", 'Informasi Tambahan:');
                    $sheet->setCellValue("A" . ($infoStartRow + 1), 'Bulan dengan Stok Masuk Terbanyak:');
                    $sheet->mergeCells("A" . ($infoStartRow + 1) . ":C" . ($infoStartRow + 1)); // Merge cells A to C for the label
                    
                    // Format: "Januari 2025" in column D
                    $bulanMasuk = $this->maxInfo['masuk'] ?? '-';
                    $tahunMasuk = $this->maxInfo['tahun_masuk'] ?? '';
                    $sheet->setCellValue("D" . ($infoStartRow + 1), $bulanMasuk . ($tahunMasuk ? ' ' . $tahunMasuk : ''));
                    
                    // Set label for "Bulan dengan Stok Keluar Terbanyak"
                    $sheet->setCellValue("A" . ($infoStartRow + 2), 'Bulan dengan Stok Keluar Terbanyak:');
                    $sheet->mergeCells("A" . ($infoStartRow + 2) . ":C" . ($infoStartRow + 2)); // Merge cells A to C for the label
                    
                    // Format: "Januari 2025" in column D
                    $bulanKeluar = $this->maxInfo['keluar'] ?? '-';
                    $tahunKeluar = $this->maxInfo['tahun_keluar'] ?? '';
                    $sheet->setCellValue("D" . ($infoStartRow + 2), $bulanKeluar . ($tahunKeluar ? ' ' . $tahunKeluar : ''));
                }
    
                // Apply styles
                $this->applyStyles($sheet, $lastColumn, $lastDataRow);
            },
        ];
    }
    
    

    protected function applyStyles(Worksheet $sheet, $lastColumn, $lastDataRow)
    {
        // Judul laporan (baris 1)
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Informasi export (baris 2-3)
        $sheet->getStyle('A2:B3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '555555'],
                'size' => 11
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ]
        ]);
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(3)->setRowHeight(20);

        // Table header style
        $headerRow = $this->startTableRow;
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4B49AC']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => 'FFFFFF']
                ]
            ]
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(25);

        // Table data style
        $dataStartRow = $headerRow + 1;
        $sheet->getStyle("A{$dataStartRow}:{$lastColumn}{$lastDataRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD']
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ]
        ]);

        // Alternating row colors
        for ($i = $dataStartRow; $i <= $lastDataRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
            if ($i % 2 === 0) {
                $sheet->getStyle("A{$i}:{$lastColumn}{$i}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F8F9FA');
            }
        }

        // Right align for number columns
        $numberColumns = $this->jenisLaporan !== 'bahan_baku' ? ['D', 'E', 'F', 'G', 'H'] : ['C', 'D', 'E', 'F', 'G'];
        foreach ($numberColumns as $col) {
            $sheet->getStyle("{$col}{$dataStartRow}:{$col}{$lastDataRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }

        // Auto filter
        // $sheet->setAutoFilter("A{$headerRow}:{$lastColumn}{$headerRow}");

        // Set column widths
        $columnWidths = $this->getColumnWidths();
        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    }

    protected function getColumnWidths(): array
    {
        if ($this->jenisLaporan !== 'bahan_baku') {
            return [
                'A' => 8,  // No.
                'B' => 12, // Periode
                'C' => 20, // Bahan Baku (reduced width)
                'D' => 15, // Ukuran
                'E' => 15, // Stok Awal
                'F' => 15, // Stok Masuk
                'G' => 15, // Stok Keluar
                'H' => 15, // Sisa Stok
            ];
        }
        
        return [
            'A' => 8,  // No.
            'B' => 20, // Bahan Baku (reduced width)
            'C' => 15, // Ukuran
            'D' => 15, // Stok Awal
            'E' => 15, // Stok Masuk
            'F' => 15, // Stok Keluar
            'G' => 15, // Sisa Stok
        ];
    }
}
