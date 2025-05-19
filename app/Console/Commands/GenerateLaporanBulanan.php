<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Laporan;
use App\Models\BahanBaku;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use Carbon\Carbon;

class GenerateLaporanBulanan extends Command
{
    protected $signature = 'generate:laporan-bulanan';
    protected $description = 'Generate laporan stok bahan baku tiap awal bulan';

    public function handle()
{
    $bahanBakus = BahanBaku::all();
    $now = Carbon::now();
    $bulan = (int)$now->format('m');
    $tahun = $now->year;

    foreach ($bahanBakus as $bahan) {
        $stok_awal = $this->getStokAwal($bahan->id, $bulan, $tahun);

        // ambil total stok masuk bulan ini
        $total_stok_masuk = StokMasuk::where('id_bahan_baku', $bahan->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah_stok_masuk');

        // ambil total stok keluar bulan ini
        $total_stok_keluar = StokKeluar::where('id_bahan_baku', $bahan->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah_stok_keluar');

        $sisa_stok = $stok_awal + $total_stok_masuk - $total_stok_keluar;

        Laporan::updateOrCreate(
            [
                'id_bahan_baku' => $bahan->id,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ],
            [
                'satuan' => $bahan->satuan ?? 'unit',
                'stok_awal' => $stok_awal,
                'total_stok_masuk' => $total_stok_masuk,
                'total_stok_keluar' => $total_stok_keluar,
                'sisa_stok' => $sisa_stok,
            ]
        );
    }

    $this->info("Laporan bulan $bulan-$tahun berhasil digenerate.");
}

private function getStokAwal($id_bahan_baku, $bulan, $tahun)
{
    // Cari laporan bulan sebelumnya
    $previous = Carbon::createFromDate($tahun, $bulan, 1)->subMonth();
    $prevLaporan = Laporan::where('id_bahan_baku', $id_bahan_baku)
        ->whereMonth('bulan', $previous->month)
        ->whereYear('tahun', $previous->year)
        ->first();

    // Jika ada laporan bulan sebelumnya, ambil sisa stok dari laporan tersebut
    return $prevLaporan ? $prevLaporan->sisa_stok : 0;
}

}