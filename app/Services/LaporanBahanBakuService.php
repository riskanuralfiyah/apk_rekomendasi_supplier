<?php

namespace App\Services;

use App\Models\BahanBaku;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LaporanBahanBakuService
{
    public function getLaporan(int $bulan, int $tahun, ?int $idBahanBaku = null): Collection
    {
        $laporan = collect();

        $bahanBakusQuery = BahanBaku::query();

        if (!empty($idBahanBaku)) {
            $bahanBakusQuery->where('id', $idBahanBaku);
        }

        $bahanBakus = $bahanBakusQuery->orderBy('nama_bahan_baku')->get();

        foreach ($bahanBakus as $bahan) {
            $id = $bahan->id;

            $createdAt = Carbon::parse($bahan->created_at);
            $bulanDataDibuat = (int) $createdAt->format('m');
            $tahunDataDibuat = (int) $createdAt->format('Y');

            if ($tahun < $tahunDataDibuat || ($tahun == $tahunDataDibuat && $bulan < $bulanDataDibuat)) {
                continue;
            }

            $sekarang = Carbon::now();
            $bulanSekarang = (int) $sekarang->format('m');
            $tahunSekarang = (int) $sekarang->format('Y');

            if ($tahun > $tahunSekarang || ($tahun == $tahunSekarang && $bulan > $bulanSekarang)) {
                continue;
            }

            // stok masuk & keluar bulan yang diminta
            $stokMasuk = DB::table('stok_masuks')
                ->where('id_bahan_baku', $id)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->sum('jumlah_stok_masuk');

            $stokKeluar = DB::table('stok_keluars')
                ->where('id_bahan_baku', $id)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->sum('jumlah_stok_keluar');

            // histori sebelumnya
            $stokMasukSebelumnya = DB::table('stok_masuks')
                ->where('id_bahan_baku', $id)
                ->where('tanggal', '<', Carbon::createFromDate($tahun, $bulan, 1))
                ->sum('jumlah_stok_masuk');

            $stokKeluarSebelumnya = DB::table('stok_keluars')
                ->where('id_bahan_baku', $id)
                ->where('tanggal', '<', Carbon::createFromDate($tahun, $bulan, 1))
                ->sum('jumlah_stok_keluar');

            // fallback stok awal jika tidak ada histori
            if ($stokMasukSebelumnya == 0 && $stokKeluarSebelumnya == 0) {
                $stokAwal = $bahan->jumlah_stok - $stokMasuk + $stokKeluar;
            } else {
                $stokAwal = $stokMasukSebelumnya - $stokKeluarSebelumnya;
            }

            $sisaStok = $stokAwal + $stokMasuk - $stokKeluar;

            if ($stokMasuk > 0 || $stokKeluar > 0 || $stokAwal > 0) {
                $laporan->push([
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nama_bahan_baku' => $bahan->nama_bahan_baku,
                    'ukuran' => $bahan->ukuran,
                    'stok_awal' => $stokAwal,
                    'stok_masuk' => $stokMasuk,
                    'stok_keluar' => $stokKeluar,
                    'sisa_stok' => $sisaStok,
                ]);
            }
        }

        return $laporan;
    }
}
