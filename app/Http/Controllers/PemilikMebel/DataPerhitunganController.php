<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\HasilRekomendasi;
use Illuminate\Support\Facades\DB;

class DataPerhitunganController extends Controller
{
    public function hitung()
    {
        // Ambil semua supplier yang memiliki penilaian
        $suppliers = Supplier::whereHas('penilaians')->get();
        $kriterias = Kriteria::with('subkriterias')->get();

        // Ambil data penilaian per supplier per kriteria
        $penilaianData = [];
        foreach ($suppliers as $supplier) {
            foreach ($kriterias as $kriteria) {
                $penilaian = Penilaian::where('id_supplier', $supplier->id)
                    ->where('id_kriteria', $kriteria->id)
                    ->first();

                if ($penilaian && $penilaian->subkriteria) {
                    $penilaianData[$supplier->id][$kriteria->id] = $penilaian->subkriteria->nilai;
                }
            }
        }

        // Hitung nilai min & max untuk setiap kriteria
        $minMax = [];
        foreach ($kriterias as $kriteria) {
            $nilaiKriteria = [];
            foreach ($suppliers as $supplier) {
                if (isset($penilaianData[$supplier->id][$kriteria->id])) {
                    $nilaiKriteria[] = $penilaianData[$supplier->id][$kriteria->id];
                }
            }

            if (!empty($nilaiKriteria)) {
                $minMax[$kriteria->id] = [
                    'min' => min($nilaiKriteria),
                    'max' => max($nilaiKriteria),
                    'kategori' => $kriteria->kategori,
                    'bobot' => $kriteria->bobot,
                ];
            }
        }

        $utilityData = [];
        $hasil = [];

        // Hitung utility dan skor akhir setiap supplier
        foreach ($suppliers as $supplier) {
            $totalSkor = 0;

            foreach ($kriterias as $kriteria) {
                if (!isset($penilaianData[$supplier->id][$kriteria->id]) || !isset($minMax[$kriteria->id])) {
                    continue;
                }

                $nilai = $penilaianData[$supplier->id][$kriteria->id];
                $min = $minMax[$kriteria->id]['min'];
                $max = $minMax[$kriteria->id]['max'];
                $bobot = $minMax[$kriteria->id]['bobot'];
                $kategori = $minMax[$kriteria->id]['kategori'];

                // Hindari pembagian 0 jika min == max
                if ($max == $min) {
                    $utility = 1;
                } else {
                    $utility = ($kategori === 'cost')
                        ? ($max - $nilai) / ($max - $min)
                        : ($nilai - $min) / ($max - $min);
                }

                $utility = round($utility, 2);
                $utilityData[$supplier->id][$kriteria->id] = $utility;
                $totalSkor += $utility * $bobot;
            }

            $hasil[] = [
                'id_supplier' => $supplier->id,
                'nama_supplier' => $supplier->nama_supplier,
                'skor_akhir' => round($totalSkor, 2),
            ];
        }

        // Urutkan berdasarkan skor akhir
        usort($hasil, fn ($a, $b) => $b['skor_akhir'] <=> $a['skor_akhir']);

        // Tambahkan peringkat
        foreach ($hasil as $index => &$row) {
            $row['peringkat'] = $index + 1;
        }

        // Simpan hasil ke database
        DB::table('hasil_rekomendasis')->truncate();
        foreach ($hasil as $row) {
            HasilRekomendasi::create([
                'id_supplier' => $row['id_supplier'],
                'skor_akhir' => $row['skor_akhir'],
                'peringkat' => $row['peringkat'],
            ]);
        }

        // Kirim data ke view dengan struktur yang sesuai
        return view('pages.PemilikMebel.DataPerhitungan.index', [
            'hasil' => $hasil,
            'suppliers' => $suppliers,
            'kriterias' => $kriterias,
            'penilaianData' => $penilaianData,
            'utilityData' => $utilityData,
        ]);
    }
}
